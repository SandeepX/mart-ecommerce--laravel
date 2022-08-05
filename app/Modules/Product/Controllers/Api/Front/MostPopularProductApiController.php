<?php

namespace App\Modules\Product\Controllers\Api\Front;

use App\Modules\Product\Helpers\PopularProductsHelper;
use App\Modules\Product\Resources\MinimalProductCollection;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use Exception;

class MostPopularProductApiController
{

    public function __construct()
    {

    }

    public function getAllMostPopularProducts(){

        try{
            if ((\Auth::guard('api')->check()) && \Auth::guard('api')->user()->isStoreUser()) {
                $storeCode = getAuthGuardStoreCode();
                $warehouseCode = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore($storeCode);
                $products = PopularProductsHelper::getAllPopularProductsOfWarehouse($warehouseCode);
                return  new MinimalProductCollection($products);
            }else{
                throw new Exception('Cannot get Data because its not Store Login!:(');
            }
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), 404);
        }
    }


    public function getLimitedMostPopularProducts(){
        try{
            if ((\Auth::guard('api')->check()) && \Auth::guard('api')->user()->isStoreUser()) {
                $storeCode = getAuthGuardStoreCode();
                $warehouseCode = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore($storeCode);
                $products = PopularProductsHelper::getLimitedPoupularProductsOfWarehouse($warehouseCode);
                return  new MinimalProductCollection($products);
            }else{
                throw new Exception('Cannot get Data because its not Store Login!:(');
            }
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), 404);
        }
    }


}
