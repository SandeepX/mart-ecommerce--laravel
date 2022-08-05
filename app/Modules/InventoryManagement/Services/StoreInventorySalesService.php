<?php


namespace App\Modules\InventoryManagement\Services;


use App\Modules\InventoryManagement\Helpers\StoreInventoryStockSalesHelper;
use App\Modules\InventoryManagement\Repositories\StoreInventoryItemDispatchedDetailRepository;
use App\Modules\InventoryManagement\Repositories\StoreInventoryItemRepository;
use App\Modules\Product\Helpers\ProductPackagingContainsHelper;
use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use Exception;
use Illuminate\Support\Facades\DB;

class StoreInventorySalesService
{
    public $inventoryStockDispatchedRepo;
    public $storeInventoryItemRepo;

    public function __construct(StoreInventoryItemDispatchedDetailRepository $inventoryStockDispatchedRepo,
                                StoreInventoryItemRepository $storeInventoryItemRepo)
    {
        $this->inventoryStockDispatchedRepo = $inventoryStockDispatchedRepo;
        $this->storeInventoryItemRepo = $storeInventoryItemRepo;
    }

    public function getStoreSalesRecordBySIIDAndPPHCode($SIIDCode,$PPHCode,$with=[],$select=['*'])
    {
        return $this->inventoryStockDispatchedRepo->select($select)
            ->with($with)
            ->getStoreStockSalesRecordDetailBySIIDCodeAndPPHCode($SIIDCode,$PPHCode);
    }

    public function findOrFailInventoryStockSalesRecordBySIIDQDCode($siidqdCode,$with=[])
    {
        try{
            $inventorySalesRecords = $this->inventoryStockDispatchedRepo
                ->with($with)
                ->findOrFailInventoryStockSalesRecordBySIIDQDCode($siidqdCode);
            if(!$inventorySalesRecords){
                throw new Exception('Sales Record Data not found');
            }
            return $inventorySalesRecords;
        }catch (Exception $e){
            throw $e;
        }

    }

    public function saveStoreInventorySalesDetail($validatedData)
    {
        try{
            $storeInventoryStockQty = StoreInventoryStockSalesHelper::getStoreInventoryStockQuantityDetail($validatedData);

            if($storeInventoryStockQty[0]->remaining_quantity < $validatedData['quantity']){
                throw new Exception('Insuffucient dispatch quantity ');
            }
            $batchDetail = $this->getInventoryBatchDetail($validatedData);
            if(!$batchDetail){
                throw new Exception('Batch Detail Of the product not found');
            }
            $validatedData['micro_unit_quantity'] = $this->getInventoryStockMicroUnitQuantity($validatedData);
            DB::beginTransaction();
                $storeInventoryStockSales = $this->inventoryStockDispatchedRepo->store($validatedData);
            DB::commit();
            return $storeInventoryStockSales;
        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateStoreInventorySalesDetail($inventoryStockSalesRecordDetail,$validatedData)
    {
        try{
            if($inventoryStockSalesRecordDetail->storeInventoryItemDetail->mrp < $validatedData['selling_price']){
                throw new Exception('Selling price must be equal or less than mrp');
            }
            $validatedData['siid_code'] = $inventoryStockSalesRecordDetail->siid_code;
            $storeInventoryStockQty = StoreInventoryStockSalesHelper::getStoreInventoryStockQuantityDetail($validatedData);
            if(!$storeInventoryStockQty){
                throw new Exception('Store Inventory Received Quantity Detail not found');
            }
            if(($storeInventoryStockQty[0]->remaining_quantity + $inventoryStockSalesRecordDetail['quantity']) < $validatedData['quantity']){
                throw new Exception('Insuffucient dispatch quantity');
            }
            $validatedData['micro_unit_quantity'] = $this->getInventoryStockMicroUnitQuantity($validatedData);
            DB::beginTransaction();
                $updateInventoryStockSales = $this->inventoryStockDispatchedRepo->update($inventoryStockSalesRecordDetail,$validatedData);
            DB::commit();
            return $validatedData;
        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function deleteStoreInventorySalesDetail($inventoryStockSalesRecordDetail)
    {
        try{
            DB::beginTransaction();
                $inventorySalesRecord = $this->inventoryStockDispatchedRepo->delete($inventoryStockSalesRecordDetail);
            DB::commit();
            return $inventorySalesRecord;
        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    private function getInventoryBatchDetail($validatedData)
    {
        try{
            $matchTheseForBatchDetail = [
                'siid_code' => $validatedData['siid_code'],
                'mrp' => $validatedData['mrp'],
                'manufacture_date' => $validatedData['manufacture_date'],
                'expiry_date' => $validatedData['expiry_date']
            ];
            $batchDetail  = $this->storeInventoryItemRepo
                ->findOrFailStoreInventoryItemBatchDetailWhichMatches($matchTheseForBatchDetail);
            return $batchDetail;
        }catch(Exception $exception){
            throw $exception;
        }
    }

    private function getInventoryStockMicroUnitQuantity($validatedData)
    {
        try{
            $productPackagingDetail = ProductPackagingContainsHelper::findProductPackagingDetailByPPHCode($validatedData['pph_code']);
            if(!$productPackagingDetail){
                throw new Exception('Product Packaging Detail Not found',404);
            }
            return ProductUnitPackagingHelper::convertToMicroUnitQuantity($validatedData['package_code'],
                $productPackagingDetail,$validatedData['quantity']);
        }catch(Exception $exception){
            throw $exception;
        }
    }

}
