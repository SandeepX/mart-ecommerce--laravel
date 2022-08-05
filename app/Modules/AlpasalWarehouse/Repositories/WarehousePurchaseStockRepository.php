<?php


namespace App\Modules\AlpasalWarehouse\Repositories;


use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseStock;

class WarehousePurchaseStockRepository
{

    public function storeWarehousePurchaseStock($validatedData){
        return WarehousePurchaseStock::create($validatedData);
    }
}
