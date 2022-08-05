<?php

namespace App\Modules\Variants\Resources;

use App\Modules\Variants\Resources\VariantValueResource;
use Illuminate\Http\Resources\Json\JsonResource;

class VariantWithValuesResource extends JsonResource
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
            'variant_name' => $this->variant_name,
            'variant_code' => $this->variant_code,
            'variant_slug' => $this->slug,
            'remarks' => $this->remarks,
            'variant_values' => VariantValueResource::collection($this->variantValues)
        ];
    }
}
