<?php


namespace App\Modules\InventoryManagement\Controllers\Api\Front;

use App\Modules\InventoryManagement\Helpers\StoreInventoryStockSalesHelper;
use App\Modules\InventoryManagement\Requests\InventoryStockSales\InventoryStockSalesStoreRequest;
use App\Modules\InventoryManagement\Requests\InventoryStockSales\InventoryStockSalesUpdateRequest;
use App\Modules\InventoryManagement\Resources\StoreInventoryItemDetail\StoreInventoryItemDetailCollection;
use App\Modules\InventoryManagement\Resources\StoreInventorySales\PackageTypeForInventorySalesCollection;
use App\Modules\InventoryManagement\Resources\StoreInventorySales\StoreInventoryProductDetailCollection;
use App\Modules\InventoryManagement\Resources\StoreInventorySales\StoreInventoryProductVariantDetailCollection;
use App\Modules\InventoryManagement\Resources\StoreInventorySales\StoreInventorySalesRecordDetailCollection;
use App\Modules\InventoryManagement\Services\InventoryCurrentStockQtyDetailService;
use App\Modules\InventoryManagement\Services\InventoryCurrentStockService;
use App\Modules\InventoryManagement\Services\StoreInventoryItemDetailService;
use App\Modules\InventoryManagement\Services\StoreInventorySalesService;
use App\Modules\Product\Helpers\ProductPackagingContainsHelper;
use Illuminate\Http\Request;


class StoreInventoryStockSalesRecordController
{
    public $inventoryStockSalesService;
    public $inventoryCurrentStockService;
    public $inventoryItemDetailService;
    public $inventoryItemRecievedService;

    public function __construct(StoreInventorySalesService $inventoryStockSalesService,
                                InventoryCurrentStockService $inventoryCurrentStockService,
                                StoreInventoryItemDetailService $inventoryItemDetailService,
                                InventoryCurrentStockQtyDetailService $inventoryItemRecievedService)
    {
        $this->inventoryStockSalesService = $inventoryStockSalesService;
        $this->inventoryCurrentStockService = $inventoryCurrentStockService;
        $this->inventoryItemDetailService = $inventoryItemDetailService;
        $this->inventoryItemRecievedService = $inventoryItemRecievedService;
    }

    public function getStoreInventoryProductSalesRecordDetail(Request $request)
    {
        try{
            $storeCode = getAuthStoreCode();
            $filterParameters = [
                'store_code' => $storeCode,
                'product_code' => $request->get('product_code'),
                'sales_from' => $request->get('expiry_date_from'),
                'sales_to' => $request->get('sales_to'),
                'perPage' => $request->get('per_page')?  $request->get('per_page') : 25,
                'page' => $request->get('page') ? (int)$request->get('page') : 1
            ];
            $storeInventoryStockDispatchedDetail = StoreInventoryStockSalesHelper::getStoreInventoryProductSalesRecordDetail($filterParameters);
            $storeInventoryStockDispatchedDetail->getCollection()->transform(function ($storeDispatchedStockPackageContains,$key){
                $storeDispatchedStockPackageContains->package_contains = implode(' ',ProductPackagingContainsHelper::getProductPackagingContainsByPPHCode($storeDispatchedStockPackageContains->pph_code));
                return $storeDispatchedStockPackageContains;
            });
            return sendSuccessResponse('Data Found',$storeInventoryStockDispatchedDetail);
        }catch(\Exception $e){
            return sendErrorResponse($e->getMessage(), 400);
        }
    }


    public function saveStoreInventoryStockSaleRecord(InventoryStockSalesStoreRequest $request)
    {
        try{
            $validatedData = $request->validated();
            $this->inventoryStockSalesService->saveStoreInventorySalesDetail($validatedData);
            return sendSuccessResponse('Data Submitted Successfully');
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(),400);
        }
    }


    public function showStoreInventorySalesRecord($SIIDCode,$PPHCode)
    {
        try{
            $with= ['storeInventoryItemDetail:siid_code,store_inventory_code,cost_price,mrp,manufacture_date,expiry_date'];
            $select = ['siidqd_code','siid_code','pph_code','package_code','quantity','selling_price','payment_type','created_at','updated_at'];
            $inventoryStockSalesRecordDetail = $this->inventoryStockSalesService
                ->getStoreSalesRecordBySIIDAndPPHCode($SIIDCode,$PPHCode,$with,$select);
            $inventoryStockSalesRecordDetail = new StoreInventorySalesRecordDetailCollection($inventoryStockSalesRecordDetail);
            return sendSuccessResponse('Data found',$inventoryStockSalesRecordDetail);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(),400);
        }
    }

    public function updateStoreInventoryStockSaleRecord(InventoryStockSalesUpdateRequest $request,$siidqdCode)
    {
        try{
            $inventoryStockSalesRecordDetail = $this->inventoryStockSalesService->findOrFailInventoryStockSalesRecordBySIIDQDCode($siidqdCode);
            $validatedData = $request->validated();
            $this->inventoryStockSalesService->updateStoreInventorySalesDetail($inventoryStockSalesRecordDetail,$validatedData);
            return sendSuccessResponse('Data Updated Successfully',$validatedData);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(),400);
        }
    }

    public function deleteStoreInventoryStockSaleRecord($siidqdCode)
    {
        try{
            $inventoryStockSalesRecordDetail = $this->inventoryStockSalesService->findOrFailInventoryStockSalesRecordBySIIDQDCode($siidqdCode);
            $this->inventoryStockSalesService->deleteStoreInventorySalesDetail($inventoryStockSalesRecordDetail);
            return sendSuccessResponse('Store Inventory Sales Record  Deleted Successfully');
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(),400);
        }
    }

    public function getStoreInventoryProduct()
    {
        try{
            $storeCode = getAuthStoreCode();
            $with = ['productDetail:product_code,product_name'];
            $select = ['product_code'];
            $inventoryProductDetail = $this->inventoryCurrentStockService
                ->getAllProductFromInventoryByStoreCode($storeCode,$select,$with);
            $inventoryProductDetail = new StoreInventoryProductDetailCollection($inventoryProductDetail);
            return sendSuccessResponse('Data Found',  $inventoryProductDetail);
        }catch(\Exception $e){
            return sendErrorResponse($e->getMessage(), 400);
        }
    }

    public function getStoreProductVariantFromInventoryByProductCode($productCode)
    {
        try{
            $storeCode = getAuthStoreCode();
            $with = ['productVariantDetail:product_variant_code,product_variant_name'];
            $select = ['product_variant_code'];
            $inventoryProductVariantDetail = $this->inventoryCurrentStockService
                ->getAllStoreProductvariantFromInventoryByProductCode($productCode,$storeCode,$select,$with);
            $inventoryProductVariantDetail = new StoreInventoryProductVariantDetailCollection($inventoryProductVariantDetail);
            return sendSuccessResponse('Data Found',  $inventoryProductVariantDetail);
        }catch(\Exception $e){
            return sendErrorResponse($e->getMessage(), 400);
        }
    }

    public function getBatchDetailOfStoreInventoryProduct($productCode,$productVariantCode)
    {
        try{
            $with = ['storeInventoryItem:store_inventory_code,siid_code,cost_price,mrp,manufacture_date,expiry_date'];
            $select = ['store_inventory_code'];
            $storeInventoryDetail = $this->inventoryCurrentStockService->findStoreInventoryProductByProductAndProductVariantCode(
                                            $productCode,$productVariantCode,$select,$with);
            if(!$storeInventoryDetail){
                throw new \Exception('Product Batch Detail Not Found');
            }
            $storeBatchDetail = (isset($storeInventoryDetail->storeInventoryItem) && !empty($storeInventoryDetail->storeInventoryItem)) ?
                new StoreInventoryItemDetailCollection($storeInventoryDetail->storeInventoryItem):null;
            return sendSuccessResponse('Data Found',  $storeBatchDetail);
        }catch(\Exception $e){
            return sendErrorResponse($e->getMessage(), 400);
        }
    }

    public function getPackingDetailOfStoreInventoryProductBySIIDCode($SIIDCode)
    {
        try{
            $select = ['siirqd_code','pph_code','siid_code'];
            $packagingDetail = $this->inventoryItemRecievedService->getItemReceivedQtyDetailBySiidCode($SIIDCode,$select);
            $packagingDetail->transform(function ($packageContains,$key){
                $packageContains->package_contains = implode(' ',ProductPackagingContainsHelper::getProductPackagingContainsByPPHCode($packageContains->pph_code));
                return $packageContains;
            });
            return sendSuccessResponse('Data Found',$packagingDetail);
        }catch(\Exception $e){
            return sendErrorResponse($e->getMessage(), 400);
        }
    }

    public function getInventoryProductPackingTypeWithQuantityByPPHCodeAndSIIDCode($SIIDCode,$PPHCode)
    {
        try{
            $packageTypeWithCurrentStockAvailable = StoreInventoryStockSalesHelper::getStoreInventoryPackageDetailWithQuantityBySIIDCodeAndPPHCode($SIIDCode,$PPHCode);
            $packageTypeWithCurrentStockAvailable = new PackageTypeForInventorySalesCollection($packageTypeWithCurrentStockAvailable);
            return sendSuccessResponse('Data Found',$packageTypeWithCurrentStockAvailable);
        }catch(\Exception $e){
            return sendErrorResponse($e->getMessage(), 400);
        }
    }

}

