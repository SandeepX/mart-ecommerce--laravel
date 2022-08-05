<?php

namespace App\Modules\Types\Resources\StoreSize;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreSizeApiResource extends JsonResource
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
            'id' => $this->id,
            'store_size_code' => $this->store_size_code,
            'store_size_name' => $this->store_size_name,
        ];
    }
}
