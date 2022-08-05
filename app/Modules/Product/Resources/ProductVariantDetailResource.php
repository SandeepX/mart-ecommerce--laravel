<?php

namespace App\Modules\Product\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantDetailResource extends JsonResource
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
            'product_variant_detail_code' => $this->product_variant_detail_code,
            'variant_value_name' => $this->variantValue->variant_value_name,
            'parent_code' => $this->variantValue->variant_code,
            'variant_value_code' => $this->variant_value_code,
            'variant' => $this->variantValue->variant->variant_name,
            'slug'=>$this->variantValue->slug
        ];
    }
}
