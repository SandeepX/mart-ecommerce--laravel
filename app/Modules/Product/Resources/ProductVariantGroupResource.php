<?php

namespace App\Modules\Product\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantGroupResource extends JsonResource
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
            'product_variant_group_code' => $this->product_variant_group_code,
            'product_code' => $this->product_code,
            'group_slug' =>$this->group_name,
            'group_variant_value_code' =>$this->group_variant_value_code,
            'combinations'=> ProductVariantCombinationResource::collection($this->groupProductVariants),
            'bulk_image_count'=> count($this->variantGroupBulkImages),
            'bulk_images'=>PVGroupBulkImageResource::collection($this->variantGroupBulkImages)
        ];
    }
}
