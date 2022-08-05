<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 12/3/2020
 * Time: 12:42 PM
 */

namespace App\Modules\Store\Repositories;


use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\Store\Models\Store;

use DB;
use Exception;

class StoreWarehouseRepository
{
    public function findOrFailStoreWarehouseConnection($storeCode,$warehouseCode){

        $storeWarehouse =DB::table('store_warehouse')->where('store_code',$storeCode)->where('warehouse_code',$warehouseCode)->first();

        if (!$storeWarehouse){
            throw new Exception('No store warehouse connection found');
        }

        return $storeWarehouse;
    }

    public function attachStoreWithWarehouses(Store $store,array $warehouseCodes){

        $store->warehouses()->attach($warehouseCodes);

        return $store;
    }

    public function syncStoreWithWarehouses(Store $store,array $warehouseCodes){

        $store->warehouses()->sync($warehouseCodes);

        return $store;
    }

    public function updateStoreWarehouseConnection(Store $store, Warehouse $warehouse,$connectionStatus){
        $store->warehouses()->updateExistingPivot($warehouse->warehouse_code, ['connection_status'=>$connectionStatus]);
    }

    //mass update connection status of warehouse
    public function updateConnectionStatusOfWarehouse(Warehouse $warehouse,$connectionStatus){
        $storeWarehouse =DB::table('store_warehouse')->where('warehouse_code',$warehouse->warehouse_code)
            ->update([
                'connection_status' =>$connectionStatus
            ]);

        return $storeWarehouse;
    }
}