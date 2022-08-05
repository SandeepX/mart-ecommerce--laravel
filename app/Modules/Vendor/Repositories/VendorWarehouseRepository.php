<?php

namespace App\Modules\Vendor\Repositories;

use App\Modules\Vendor\Models\VendorWareHouse;

class VendorWarehouseRepository
{
    public function getAllVendorWarehouses($vendor){
        return $vendor->vendorWarehouses;
    }

    public function findVendorWarehouseByCode($vendorWarehouseCode){
        return VendorWareHouse::findOrFail($vendorWarehouseCode);
    }
    
    public function storeVendorWarehouse($vendor, $validatedVendorWarehouse){
        return $vendor->vendorWarehouses()->create($validatedVendorWarehouse);
    }

    public function updateVendorWarehouse($vendorWarehouse, $validatedVendorWarehouse){
        $vendorWarehouse->update($validatedVendorWarehouse);
        return $vendorWarehouse->fresh();
    }

    public function deleteVendorWarehouse($vendorWarehouse){
        $vendorWarehouse->delete();
        return $vendorWarehouse;
    }
}