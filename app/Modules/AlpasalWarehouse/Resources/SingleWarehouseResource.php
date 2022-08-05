<?php

namespace App\Modules\AlpasalWarehouse\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleWarehouseResource extends JsonResource
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
            'warehouse_code' => $this->warehouse_code,
            'warehouse_name' => $this->warehouse_name,
            'warehouse_type' => $this->warehouseType->warehouse_type_name,
            'slug' => $this->slug,
            'remarks' => $this->remarks,
            'warehouse_location_name' => $this->location->location_name,
            'warehouse_landmark' => $this->landmark_name,
            'warehouse_latitude' => $this->latitude,
            'warehouse_longitude' => $this->longitude,
        ];
    }
}
