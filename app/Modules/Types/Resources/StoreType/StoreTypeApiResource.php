<?php

namespace App\Modules\Types\Resources\StoreType;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreTypeApiResource extends JsonResource
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
            'store_type_code' => $this->store_type_code,
            'store_type_name' => $this->store_type_name,
        ];
    }
}
