<?php


namespace App\Modules\AlpasalWarehouse\Repositories\StockTransfer;

use App\Modules\AlpasalWarehouse\Models\StockTransfer\WarehouseReceivedStockTransferDetail;

class WarehouseReceiveStockTransferDetailsRepository
{
    public function save($validatedData){
        $warehouseReceiveStockTransferDetails = WarehouseReceivedStockTransferDetail::create($validatedData);
        return  $warehouseReceiveStockTransferDetails->fresh();
    }
}
