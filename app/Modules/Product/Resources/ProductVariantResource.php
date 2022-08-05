<?php

namespace App\Modules\Product\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
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
            'product_variant_code' => $this->product_variant_code,
            'product_variant_name' => $this->product_variant_name,
         //   'sku' => $this->sku,
           // 'price' => $this->price,
          //  'remarks' => $this->remarks,
            'product_variant_images' => ProductVariantImageResource::collection($this->images),
            'product_variant_details' => ProductVariantDetailResource::collection($this->details),
            'variant_value_code_combination' => implode('-',$this->details->pluck('variant_value_code')->toArray()),

        ];
    }
}
