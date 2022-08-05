<?php
namespace App\Modules\Location\Resources\LocationHierarchy;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationHierarchyApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            'id' => $this->id,
            'location_code' => $this->location_code,
            'location_name' => $this->location_name,
        ];
    }
}
