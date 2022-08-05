<?php

namespace App\Modules\Variants\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VariantValueResource extends JsonResource
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
            //'id' => $this->id,
            'variant_value_name' => $this->variant_value_name,
            'variant_value_code' => $this->variant_value_code,
            'slug'               => $this->slug,
            'remarks'            => $this->remarks
        ];
    }
}
