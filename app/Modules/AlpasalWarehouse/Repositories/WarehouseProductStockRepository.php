<?php


namespace App\Modules\AlpasalWarehouse\Repositories;


use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductHelper;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductStock;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductStockView;
use App\Modules\AlpasalWarehouse\Models\WarehouseSalesStock;
use App\Modules\AlpasalWarehouse\Models\WarehouseSalesReturnStock;

class WarehouseProductStockRepository
{

    public function getProductStockHistories($warehouseProductMasterCode){
        return WarehouseProductStock::where('warehouse_product_master_code',$warehouseProductMasterCode)
            ->latest()
            ->get();
    }

    public function findCurrentProductStockInWarehouse($warehouseProductMasterCode){
     //  return WarehouseProductStockView::where('code',$warehouseProductMasterCode)->first();
        return WarehouseProductMaster::where('warehouse_product_master_code',$warehouseProductMasterCode)->first();
    }

    public function storeWarehouseProductStock($validatedData){
        return WarehouseProductStock::create($validatedData);
    }

    public function storeWarehouseSalesStock($validatedData){
        return WarehouseSalesStock::create($validatedData);
    }

    public function storeWarehouseSalesReturnStock($validatedData){
        return WarehouseSalesReturnStock::create($validatedData);
    }

}
