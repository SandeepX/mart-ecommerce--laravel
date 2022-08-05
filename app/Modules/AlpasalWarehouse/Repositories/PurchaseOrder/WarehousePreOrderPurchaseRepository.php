<?php


namespace App\Modules\AlpasalWarehouse\Repositories\PurchaseOrder;


use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderPurchaseOrder;

class WarehousePreOrderPurchaseRepository
{
    public function storeWarehousePurchaseOrder($validatedPurchaseOrder){

        $purchaseOrder=WarehousePreOrderPurchaseOrder::create($validatedPurchaseOrder)->fresh();

        return $purchaseOrder;

    }

}
