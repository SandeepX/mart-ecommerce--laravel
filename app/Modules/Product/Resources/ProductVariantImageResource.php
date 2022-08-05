<?php

namespace App\Modules\Product\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantImageResource extends JsonResource
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
            'product_image_variant_code' => $this->product_variant_image_code,
            'image' => url('/uploads/products/variants/'.$this->image),
        ];
    }
}
