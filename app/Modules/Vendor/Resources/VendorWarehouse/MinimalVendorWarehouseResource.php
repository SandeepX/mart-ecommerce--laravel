<?php

namespace App\Modules\Vendor\Resources\VendorWarehouse;

use Illuminate\Http\Resources\Json\JsonResource;

class MinimalVendorWarehouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'vendor_warehouse_code' => $this->vendor_warehouse_code,
            'vendor_warehouse_name' => $this->vendor_warehouse_name,
        ];
    }
}
