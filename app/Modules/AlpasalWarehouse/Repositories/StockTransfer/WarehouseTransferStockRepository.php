<?php


namespace App\Modules\AlpasalWarehouse\Repositories\StockTransfer;


use App\Modules\AlpasalWarehouse\Models\StockTransfer\WarehouseTransferStock;

class WarehouseTransferStockRepository
{

    public function save($validatedData){
        $warehouseStockTransfer = WarehouseTransferStock::create($validatedData);
        return $warehouseStockTransfer->fresh();
    }

}
