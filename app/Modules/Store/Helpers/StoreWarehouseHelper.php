<?php


namespace App\Modules\Store\Helpers;


use Illuminate\Support\Facades\DB;
use Exception;

class StoreWarehouseHelper
{

    public static function getActiveWarehousesCodeAssociatedWithStore($storeCode){

        return DB::table('store_warehouse')->where('store_code',$storeCode)
            ->where('connection_status',1)
            ->pluck('warehouse_code')->toArray();
    }

    public static function getFirstActiveWarehouseCodeAssociatedWithStore($storeCode)
    {
        $warehouse =  DB::table('store_warehouse')
            ->select('warehouse_code')
            ->where('store_code', $storeCode)
            ->where('connection_status', 1)
            ->first();
         if(!$warehouse)
         {
             throw new Exception('can not find warehouse associated with  the store');
         }
        return $warehouse->warehouse_code;
    }

    public static function getFirstConnectedWarehouse($storeCode){

        return DB::table('store_warehouse')->where('store_code',$storeCode)
            ->where('connection_status',1)
            ->join('warehouses',
                'warehouses.warehouse_code',
                '=',
                'store_warehouse.warehouse_code'
            )
            ->select('store_warehouse.warehouse_code',
                'store_warehouse.connection_status','store_warehouse.created_at as connected_date',
                'warehouses.warehouse_name','warehouses.landmark_name'
            )->first();
    }
}
