<?php

namespace App\Modules\Product\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantCombinationResource extends JsonResource
{


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $combinationCodes = $this->details->pluck('variant_value_code')->toArray();

        return [
            'combinations_values' => ProductVariantDetailResource::collection($this->details),
            'variant_code'=>$this->product_variant_code,
            'combination_slug' => implode('-',$combinationCodes),
            'image_count'=>count($this->images),
            'images'=>ProductVariantImageResource::collection($this->images)
        ];
    }
}
