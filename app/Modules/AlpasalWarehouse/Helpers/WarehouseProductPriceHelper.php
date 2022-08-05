<?php


namespace App\Modules\AlpasalWarehouse\Helpers;


use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductPriceMaster;

class WarehouseProductPriceHelper
{

    public static function findWarehouseProductPriceByWarehouseCode($warehouseCode,$productCode,$productVariantCode=null){
        $warehouseProductMaster=WarehouseProductHelper::findWarehouseProductByWarehouseCode($warehouseCode,$productCode,$productVariantCode);
        if ($warehouseProductMaster){
            return self::findWarehouseProductPriceByWarehouseProductCode($warehouseProductMaster->warehouse_product_master_code);
        }

    }

    public static function findWarehouseProductPriceByWarehouseProductCode($warehouseProductMasterCode){

        return WarehouseProductPriceMaster::where('warehouse_product_master_code',$warehouseProductMasterCode)->first();
    }
}
