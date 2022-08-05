<?php

namespace App\Modules\AlpasalWarehouse\Repositories\PurchaseOrder;

use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseOrderReceivedDetail;

class WarehousePurchaseOrderReceiveDetailsRepository
{
    public function savePurchaseOrderReceiveDetail($validatedData){
        $purchaseOrderReceiveDetails = WarehousePurchaseOrderReceivedDetail::create($validatedData);
        return $purchaseOrderReceiveDetails->fresh();
    }

}
