<?php

namespace App\Modules\AlpasalWarehouse\Repositories;

use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Store\Models\Store;
use App\Modules\Store\Models\StoreOrderDispatchDetail;

class WarehouseRepository
{
    use ImageService;

    public function getAllWarehouses(){
        return Warehouse::latest()->get();
    }

    public function getAllWarehousesByType($warehouseType){
        return Warehouse::whereHas('warehouseType',function ($query) use ($warehouseType){
            $query->where('slug',$warehouseType);
        })->latest()->get();
    }

    public function findWarehouseByCode($warehouseCode){
        return Warehouse::findOrFail($warehouseCode);
    }

    public function findOrFailByCode($warehouseCode,$with=[],$select = '*'){

        return Warehouse::with($with)->select($select)->where('warehouse_code',$warehouseCode)->firstOrFail();
    }

    public function storeWarehouse($validatedWarehouse){
        $validatedWarehouse['slug'] = make_slug($validatedWarehouse['warehouse_name']);

        if(isset($validatedWarehouse['warehouse_logo'])){
            $fileNameToStore = $this->storeImageInServer($validatedWarehouse['warehouse_logo'], Warehouse::IMAGE_PATH);
            $validatedWarehouse['warehouse_logo'] = $fileNameToStore;
        }

        return Warehouse::create($validatedWarehouse);
    }

    public function updateWarehouse($validatedWarehouse, $warehouse){
        $validatedWarehouse['slug'] = make_slug($validatedWarehouse['warehouse_name']);

        if(isset($validatedWarehouse['warehouse_logo'])){
            $this->deleteImageFromServer(Warehouse::IMAGE_PATH, $warehouse->warehouse_logo);
            $validatedWarehouse['warehouse_logo'] = $this->storeImageInServer($validatedWarehouse['warehouse_logo'], Warehouse::IMAGE_PATH);
        }

        $warehouse->update($validatedWarehouse);
        return $warehouse->fresh();
    }

    public function deleteWarehouse($warehouse){
        $warehouse->delete();
        return $warehouse;
    }

//    public function findorFailStoreOrderDispatchDetail($storeOrderCode){
//        return StoreOrderDispatchDetail::where('store_order_code',$storeOrderCode)->first();
//    }

public function findOrFailStoreByCode($storeCode,$warehouseCode)
{
    return Store::join('store_warehouse',function($join) use($storeCode,$warehouseCode){
        $join->on('store_warehouse.store_code','=','stores_detail.store_code')
        ->where('stores_detail.store_code',$storeCode)
        ->where('store_warehouse.warehouse_code',$warehouseCode);
    }
    )->first();
}
    public function getWarehouseUser($warehouseCode)
    {
        $warehouse=Warehouse::join('warehouse_user','warehouse_user.warehouse_code','=','warehouses.warehouse_code')
            ->join('users',function($join) use ($warehouseCode){
                $join->on('users.user_code','=','warehouse_user.user_code')
                    ->where('warehouse_user.warehouse_code','=',$warehouseCode);
            })
            ->first();
        return $warehouse;
    }

    public function getOtherWarehouses()
    {
        $warehouses = Warehouse::where('warehouse_code', '!=', getAuthWarehouseCode())->get();
        return $warehouses;
    }
}

