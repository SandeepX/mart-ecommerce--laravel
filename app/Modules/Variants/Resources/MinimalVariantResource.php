<?php

namespace App\Modules\Variants\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MinimalVariantResource extends JsonResource
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
        ];
    }
}
