<?php


namespace App\Modules\AlpasalWarehouse\Repositories;


use App\Modules\AlpasalWarehouse\Models\WarehouseProductPackagingUnitDisableList;

class WarehouseProductPackagingDisableListRepo
{

    public function saveUnitDisableList(array $validatedData){
        //dd($validatedData);
        WarehouseProductPackagingUnitDisableList::create($validatedData);
    }

    public function massDeleteUnitDisableListByPreOrderProductCode(array $warehouseProductMastersCode){
        //dd($validatedData);
        WarehouseProductPackagingUnitDisableList::whereIn('warehouse_product_master_code',$warehouseProductMastersCode)->delete();
    }

}
