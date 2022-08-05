<?php


namespace App\Modules\Store\Helpers;

use Illuminate\Support\Facades\DB;
class StoreProductHelper
{

    public static function isProductBoughtByStoreFromWarehouse($storeCode,$warehouseCode,
                                                               $productCode){

        $storeOrderDetails = DB::table('store_order_details')->where('store_order_details.warehouse_code',$warehouseCode)
            ->where('store_order_details.product_code',$productCode)
            ->join('store_orders', function ($join) use($storeCode){
                $join->on('store_order_details.store_order_code', '=', 'store_orders.store_order_code')
                    ->where('store_orders.store_code',$storeCode);
            })->where('store_order_details.acceptance_status','accepted')->count();

        if ($storeOrderDetails > 0){
            return true;
        }
        return false;
    }
}
