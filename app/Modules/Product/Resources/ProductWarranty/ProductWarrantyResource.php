<?php

namespace App\Modules\Product\Resources\ProductWarranty;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductWarrantyResource extends JsonResource
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
            'warranty_code' => $this->warranty_code,
            'warranty_name' => $this->warranty_name,
            'remarks' => $this->remarks
        ];
    }
}
