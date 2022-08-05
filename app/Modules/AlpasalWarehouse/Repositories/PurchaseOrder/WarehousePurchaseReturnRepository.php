<?php


namespace App\Modules\AlpasalWarehouse\Repositories\PurchaseOrder;


use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseReturn;
use Carbon\Carbon;

class WarehousePurchaseReturnRepository
{
    public function findOrFailPurchaseReturnByCode($code,$with=[]){

        return WarehousePurchaseReturn::with($with)->where('warehouse_purchase_return_code',$code)->firstOrFail();
    }

    public function createWarehousePurchaseReturn($validatedData){

        return WarehousePurchaseReturn::create($validatedData)->fresh();
    }

    public function updateStatus(WarehousePurchaseReturn $warehousePurchaseReturn,$validatedData){

        $warehousePurchaseReturn->status = $validatedData['status'];
        $warehousePurchaseReturn->status_remarks = $validatedData['status_remarks'];
        $warehousePurchaseReturn->accepted_return_quantity = $validatedData['accepted_return_quantity'];
        $warehousePurchaseReturn->status_responded_by = getAuthUserCode();
        $warehousePurchaseReturn->status_responded_at = Carbon::now();

        $warehousePurchaseReturn->save();

        return $warehousePurchaseReturn;
    }
}
