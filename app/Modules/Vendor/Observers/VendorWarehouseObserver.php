<?php

namespace App\Modules\Vendor\Observers;

use App\Modules\Vendor\Models\VendorWareHouse;

class VendorWarehouseObserver
{
    public function creating(VendorWareHouse $vendorWareHouse)
    {
        $authUserCode = getAuthUserCode();
        $vendorWareHouse->vendor_warehouse_code = $vendorWareHouse->generateVendorWarehouseCode();
        $vendorWareHouse->created_by = $authUserCode;
        $vendorWareHouse->updated_by = $authUserCode;
    }

    public function updating(VendorWareHouse $vendorWareHouse){
        $vendorWareHouse->updated_by = getAuthUserCode();
    }

    public function deleting(VendorWareHouse $vendorWareHouse){
        $vendorWareHouse->deleted_by = getAuthUserCode();
        $vendorWareHouse->save();
    }
}

