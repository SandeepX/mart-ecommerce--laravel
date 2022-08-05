<?php


namespace App\Modules\InventoryManagement\Controllers\Api\Front;

use App\Modules\InventoryManagement\Helpers\InventoryPurchaseStockHelper;
use App\Modules\InventoryManagement\Requests\InventoryCurrentStock\InventoryCurrentStockStoreRequest;
use App\Modules\InventoryManagement\Services\InventoryCurrentStockService;
use App\Modules\Product\Helpers\ProductPackagingContainsHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class InventoryPurchaseStockController
{
    public $inventoryCurrentStockService;

    public function __construct(InventoryCurrentStockService $inventoryCurrentStockService)
    {
        $this->inventoryCurrentStockService = $inventoryCurrentStockService;
    }

    public function getStoreInventoryProductCurrentStockDetail(Request $request)
    {
        try{
            $storeCode = getAuthStoreCode();
            $filterParameters = [
                'store_code' => $storeCode,
                'product_code' => $request->get('product_code'),
                'expiry_date_from' => $request->get('expiry_date_from'),
                'expiry_date_to' => $request->get('expiry_date_to'),
                'perPage' => $request->get('per_page')?  $request->get('per_page') : 25,
                'page' => $request->get('page') ? (int)$request->get('page') : 1
            ];
            $storeCurrentStockDetail = InventoryPurchaseStockHelper::getStoreInventoryCurrentProductStockDetail($filterParameters);
            $storeCurrentStockDetail->getCollection()->transform(function ($storeCurrentStockPackageContains,$key){
                $storeCurrentStockPackageContains->package_contains = implode(' ',ProductPackagingContainsHelper::getProductPackagingContainsByPPHCode($storeCurrentStockPackageContains->pph_code));
                return $storeCurrentStockPackageContains;
            });
            return sendSuccessResponse('Data Found',$storeCurrentStockDetail);
        }catch(\Exception $e){
            return sendErrorResponse($e->getMessage(), 400);
        }
    }

    public function saveStoreCurrentStockProductDetail(InventoryCurrentStockStoreRequest $request)
    {
        DB::beginTransaction();
        try{
            $storeCode = getAuthStoreCode();
            $validatedData = $request->validated();
            $dispatchedStoreProductDetail = InventoryPurchaseStockHelper::getDispatchedProductVariantDetailToStoreByProductCode(
                                            $validatedData['product_code'],
                                            $storeCode,
                                            $validatedData['product_variant_code']
            );
            if(empty($dispatchedStoreProductDetail)){
                throw new Exception('Invalid Product Detail Data Submitted',400);
            }
            $storeCurrentStockDetail = $this->inventoryCurrentStockService->saveStoreProductCurrentStockDetail($validatedData);
            DB::commit();
            return sendSuccessResponse('Data Submitted Successfully');
        }catch(\Exception $e){
            DB::rollBack();
            return sendErrorResponse($e->getMessage(), 400);
        }
    }

    public function getStorePurchasedProducts()
    {
        try{
            $storeCode = getAuthStoreCode();
            $productDetail = InventoryPurchaseStockHelper::getAllStorePurchasedProductsForCurrentStock($storeCode);
            return sendSuccessResponse('Data Found',  $productDetail);
        }catch(\Exception $e){
            return sendErrorResponse($e->getMessage(), 400);
        }
    }

    public function getProductVariantByProductCode($productCode)
    {
        try{
            $storeCode = getAuthStoreCode();
            $productVariantDetail = InventoryPurchaseStockHelper::getDispatchedProductVariantDetailToStoreByProductCode($productCode,$storeCode);
            return sendSuccessResponse('Data Found',  $productVariantDetail);
        }catch(\Exception $e){
            return sendErrorResponse($e->getMessage(), 400);
        }
    }

    public function getProductPackagingContains(Request $request)
    {
        try{
            $productCode = $request->product_code;
            $productVariantCode= $request->product_variant_code;
            $productPackagingHistroyDetail = ProductPackagingContainsHelper::getProductPackagingContainsByPackagingHistroy($productCode,$productVariantCode);
            return sendSuccessResponse('Data Found',$productPackagingHistroyDetail);
        }catch (Exception $exception){
            return sendSuccessResponse($exception->getMessage(),400);
        }
    }

    public function getProductPackagingTypeByPPHCode($pphCode)
    {
        try{
            $packageTypes = InventoryPurchaseStockHelper::getProductPackageDetailByPPHCode($pphCode);
            return sendSuccessResponse('Data Found',$packageTypes);
        }catch (Exception $exception){
            return sendSuccessResponse($exception->getMessage(),400);
        }
    }

}
