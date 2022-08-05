<?php


namespace App\Modules\InventoryManagement\Services;


use App\Modules\InventoryManagement\Repositories\StoreInventoryItemRecievedQtyRepository;
use App\Modules\InventoryManagement\Repositories\StoreInventoryItemRepository;
use App\Modules\InventoryManagement\Repositories\StoreInventoryRepository;
use App\Modules\Product\Helpers\ProductPackagingContainsHelper;
use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use Exception;
use Illuminate\Support\Facades\DB;

class InventoryCurrentStockService
{
    public $storeInventoryRepo;
    public $storeInventoryItemRepo;
    public $inventoryItemRecievedQtyRepo;

    public function __construct(StoreInventoryRepository $storeInventoryRepo,
                                StoreInventoryItemRepository $storeInventoryItemRepo,
                                StoreInventoryItemRecievedQtyRepository $inventoryItemRecievedQtyRepo
    )
    {
        $this->storeInventoryRepo = $storeInventoryRepo;
        $this->storeInventoryItemRepo = $storeInventoryItemRepo;
        $this->inventoryItemRecievedQtyRepo = $inventoryItemRecievedQtyRepo;
    }

    public function findStoreInventoryProductByProductAndProductVariantCode($productCode,$productVariantCode,
                                                                            $select=['*'],$with=[])
    {
        return $this->storeInventoryRepo->select($select)
            ->with($with)
            ->findStoreInventoryProductByProductAndProductVariantCode($productCode,$productVariantCode);
    }

    public function getAllProductFromInventoryByStoreCode($storeCode,$select=['*'],$with=[])
    {
        try{
            $inventoryProducts =  $this->storeInventoryRepo->select($select)
                ->with($with)
                ->getAllProductFromInventoryByStoreCode($storeCode);
            if(count($inventoryProducts) < 1){
                throw new Exception('No Product Available In Store Inventory');
            }
            return $inventoryProducts;
        }catch(Exception $e){
            throw $e;
        }
    }

    public function getAllStoreProductvariantFromInventoryByProductCode($productCode,$storeCode,$select=['*'],$with=[])
    {
       return $this->storeInventoryRepo->select($select)
                ->with($with)
                ->getAllStoreProductvariantFromInventoryByProductCode($productCode,$storeCode);
    }

    public function findStoreInventoryItemDetailBySignature($signature)
    {
        return $this->storeInventoryItemRepo->findStoreInventoryItemDetailBySignature($signature);
    }

    public function saveStoreProductCurrentStockDetail($validatedData)
    {
        try{
            $validatedData['store_code'] = getAuthStoreCode();
            DB::beginTransaction();

            $storeInventoryProductDetail = $this->findStoreInventoryProductByProductAndProductVariantCode(
                    $validatedData['product_code'],$validatedData['product_variant_code']);




            if($storeInventoryProductDetail){
                $inventoryItemData = $this->getStoreInventorItemData($storeInventoryProductDetail,$validatedData);
                $storeInventoryItemDetail = $this->findStoreInventoryItemDetailBySignature($inventoryItemData['signature']);

                if($storeInventoryItemDetail){
                    //when batch is same but have to entry qty detail
                    $inventoryItemRecievedQtyData = $this->getStoreInventoryItemRecievedQtyData($storeInventoryItemDetail,$validatedData);
                    $storeInventoryReceivedItemQty = $this->saveInventoryItemRecievedQtyDetail($inventoryItemRecievedQtyData);
                    $storeInventoryItemUpdate = $this->updateInventoryItemDetail($storeInventoryItemDetail,$inventoryItemData);
                }else{
                    //when batch is new but product is same
                    $storeInventoryItem = $this->saveInventoryItemDetail($inventoryItemData);
                    $inventoryItemRecievedQtyData = $this->getStoreInventoryItemRecievedQtyData($storeInventoryItem,$validatedData);
                    $storeInventoryReceivedItemQty = $this->saveInventoryItemRecievedQtyDetail($inventoryItemRecievedQtyData);
                }
            }
            // when product is new
            else{
                $storeInventory = $this->storeInventoryRepo->store($validatedData);
                if($storeInventory){
                    $inventoryItemData = $this->getStoreInventorItemData($storeInventory,$validatedData);
                    $storeInventoryItem = $this->saveInventoryItemDetail($inventoryItemData);
                    if($storeInventoryItem){
                        $inventoryItemRecievedQtyData = $this->getStoreInventoryItemRecievedQtyData($storeInventoryItem,$validatedData);
                        $storeInventoryRecievedItemQty = $this->saveInventoryItemRecievedQtyDetail($inventoryItemRecievedQtyData);
                    }
                }
            }
            DB::commit();
            return true;
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function getStoreInventorItemData($storeInventory,$validatedData)
    {
         $storeInventoryItem = [
            'store_inventory_code' => $storeInventory->store_inventory_code,
            'cost_price' =>truncateNumberAfterDecimal($validatedData['cost_price'],2),
            'mrp' => truncateNumberAfterDecimal($validatedData['mrp'],2),
            'manufacture_date' =>$validatedData['manufacture_date'],
            'expiry_date' => $validatedData['expiry_date']
        ];
        $storeInventoryItem['signature'] = $this->getSignature($storeInventoryItem);
        return $storeInventoryItem;
     }

    public function getSignature($storeInventoryItem)
    {
        $payload =  http_build_query($storeInventoryItem);
        return hash_hmac('sha256',$payload,env('APP_KEY'));
    }

    public function saveInventoryItemDetail($inventoryItemData)
    {
        try{
            DB::beginTransaction();
            $storeInventoryItem = $this->storeInventoryItemRepo->store($inventoryItemData);
            DB::commit();
            return $storeInventoryItem;
        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

    public function getStoreInventoryItemRecievedQtyData($storeInventoryItem,$validatedData)
    {
        $productPackagingDetail = ProductPackagingContainsHelper::findProductPackagingDetailByPPHCode($validatedData['pph_code']);
        if(!$productPackagingDetail){
            throw new Exception('Product Packaging Detail Not found',404);
        }
        $inventoryItemRecievedQtyData = [
            'siid_code' => $storeInventoryItem->siid_code,
            'package_code' => $validatedData['package_code'],
            'quantity' => $validatedData['quantity'],
            'source' => 'manual_entry',
            'pph_code' => $validatedData['pph_code'],
            'micro_unit_quantity' => ProductUnitPackagingHelper::convertToMicroUnitQuantity($validatedData['package_code'],
                $productPackagingDetail,$validatedData['quantity'])
        ];
        return $inventoryItemRecievedQtyData;
    }

    public function saveInventoryItemRecievedQtyDetail($inventoryItemRecievedQtyData)
    {
        try{
            DB::beginTransaction();
            $storeInventoryRecieveItemQty = $this->inventoryItemRecievedQtyRepo->store($inventoryItemRecievedQtyData);
            DB::commit();
            return $storeInventoryRecieveItemQty;
        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateInventoryItemDetail($inventoryItemDetail,$inventoryItemData)
    {
        try{
            DB::beginTransaction();
            $storeInventoryItem = $this->storeInventoryItemRepo->update($inventoryItemDetail,$inventoryItemData);
            DB::commit();
            return $storeInventoryItem;
        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

}
