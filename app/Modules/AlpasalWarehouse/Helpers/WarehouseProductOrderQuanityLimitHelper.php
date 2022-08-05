<?php


namespace App\Modules\AlpasalWarehouse\Helpers;


use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\Store\Helpers\StoreWarehouseHelper;

class WarehouseProductOrderQuanityLimitHelper
{
    public static function findWarehouseProductOrderQuantityLimit($warehouseProductMasterCode)
    {
        $storeCode = getAuthStoreCode();
        $warehouseCode = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore($storeCode);
        $warehouseProductOrderLimit = WarehouseProductMaster::where('warehouse_product_master_code', $warehouseProductMasterCode)
            ->where('warehouse_code', $warehouseCode)
            ->first();
        return $warehouseProductOrderLimit;

    }
}



