<?php

namespace App\Modules\AlpasalWarehouse\Repositories\StockTransfer;

use App\Modules\AlpasalWarehouse\Models\StockTransfer\WarehouseTransferLoss;

class WarehouseTransferLossRepository
{

    public function save($validatedData){
        $warehouseTransferLossDetails = WarehouseTransferLoss::create($validatedData);
        return  $warehouseTransferLossDetails->fresh();
    }

}
