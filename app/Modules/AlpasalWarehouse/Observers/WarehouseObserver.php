<?php

namespace App\Modules\AlpasalWarehouse\Observers;

use App\Modules\AlpasalWarehouse\Models\Warehouse;

class WarehouseObserver
{
    public function creating(Warehouse $warehouse)
    {
        $authUserCode = getAuthUserCode();
        $warehouse->warehouse_code = $warehouse->generateWarehouseCode();
        $warehouse->created_by = $authUserCode;
        $warehouse->updated_by = $authUserCode;
    }

    public function updating(Warehouse $warehouse){
        $warehouse->updated_by = getAuthUserCode();
    }

    public function deleting(Warehouse $warehouse){
        $warehouse->deleted_by = getAuthUserCode();
        $warehouse->save();
    }
}

