<?php

namespace App\Modules\Vendor\Resources\VendorWarehouse;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleVendorWarehouseResource extends JsonResource
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
            'vendor_warehouse_location_name' => $this->location->location_name,
            'warehouse_landmark' => $this->landmark_name,
            'warehouse_latitude' => $this->latitude,
            'warehouse_longitude' => $this->longitude,
        ];
    }
}
