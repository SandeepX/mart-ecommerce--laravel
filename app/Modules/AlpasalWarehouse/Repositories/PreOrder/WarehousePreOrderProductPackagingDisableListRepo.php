<?php


namespace App\Modules\AlpasalWarehouse\Repositories\PreOrder;


use App\Modules\AlpasalWarehouse\Models\PreOrder\PreOrderPackagingUnitDisableList;
use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderProduct;

class WarehousePreOrderProductPackagingDisableListRepo
{
    public function saveUnitDisableListOfProduct(
        WarehousePreOrderProduct $warehousePreOrderProduct,array $validatedData){

        $warehousePreOrderProduct->packagingDisableList()->createMany($validatedData);
    }

    public function saveUnitDisableList(array $validatedData){
        //dd($validatedData);
        PreOrderPackagingUnitDisableList::create($validatedData);
    }

    public function massDeleteUnitDisableListByPreOrderProductCode(array $warehousePreOrderProductsCode){
        //dd($validatedData);
        PreOrderPackagingUnitDisableList::whereIn('warehouse_preorder_product_code',$warehousePreOrderProductsCode)->delete();
    }
}
