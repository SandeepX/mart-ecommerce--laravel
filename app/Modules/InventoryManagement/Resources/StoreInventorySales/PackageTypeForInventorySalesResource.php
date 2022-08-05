<?php


namespace App\Modules\InventoryManagement\Resources\StoreInventorySales;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageTypeForInventorySalesResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'package_code' => $this->package_code,
            'package_name' => $this->package_name,
            'store_in_qty' => $this->store_in_qty,
            'store_out_qty' => $this->store_out_qty,
            'store_remaining_quantity' => $this->store_remaining_quantity,
        ];
    }
}








