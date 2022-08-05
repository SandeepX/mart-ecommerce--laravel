<?php

namespace App\Modules\Product\Resources\ProductWarranty;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductWarrantyDetailResource extends JsonResource
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
            'warranty_policy' => $this->warranty_policy,
            'warranty_name' => $this->productWarranty->warranty_name,
            'remarks' => $this->productWarranty->remarks
        ];
    }
}
