<?php

namespace App\Modules\Location\Resources\LocationHierarchy;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationHierarchyResource extends JsonResource
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
           // 'id' => $this->id,
            'location_name' => $this->location_name,
            'location_name_devanagari' => $this->location_name_devanagari,
            'location_code' => $this->location_code,
            'location_type' => $this->location_type,
        ];
    }
}
