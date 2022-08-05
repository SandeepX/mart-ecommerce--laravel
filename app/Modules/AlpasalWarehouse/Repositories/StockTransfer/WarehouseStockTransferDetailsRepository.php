<?php


namespace App\Modules\AlpasalWarehouse\Repositories\StockTransfer;


use App\Modules\AlpasalWarehouse\Models\StockTransfer\WarehouseStockTransferDetail;

class WarehouseStockTransferDetailsRepository
{

    public function save($validatedData){
        $warehouseStockTransferDetails = WarehouseStockTransferDetail::create($validatedData);
        return  $warehouseStockTransferDetails->fresh();
    }

}
