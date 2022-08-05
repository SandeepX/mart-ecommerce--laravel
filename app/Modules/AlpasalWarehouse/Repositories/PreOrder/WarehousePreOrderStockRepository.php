<?php


namespace App\Modules\AlpasalWarehouse\Repositories\PreOrder;


use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderStock;

class WarehousePreOrderStockRepository
{

    public function storeWarehousePreOrderStock($validatedData){
        return WarehousePreOrderStock::create($validatedData);
    }
}
