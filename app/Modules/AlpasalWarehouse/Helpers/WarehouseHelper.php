<?php


namespace App\Modules\AlpasalWarehouse\Helpers;


use App\Modules\AlpasalWarehouse\Models\Warehouse;

class WarehouseHelper
{

    public static function getAllOpenWarehousesCode(){
        return Warehouse::whereHas('warehouseType',function ($query){
            $query->where('slug','open');
        })->latest()->pluck('warehouse_code')->toArray();
    }

    public static function getAllWarehousesWithConnectedStores($with = []){
        $warehouses = Warehouse::with($with)
            ->get();
        return $warehouses;
    }
}
