<?php

namespace App\Modules\Vendor\Resources\VendorWarehouse;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorWarehouseListResource extends JsonResource
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
            'vendor_warehouse_location_code' => $this->location->location_code,
            'warehouse_landmark' => $this->landmark_name,
            'warehouse_latitude' => $this->latitude,
            'warehouse_longitude' => $this->longitude,
        ];
    }
}
