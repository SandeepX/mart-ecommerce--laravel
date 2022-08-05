<?php

namespace App\Modules\Brand\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [

            'brand_name' => $this->brand_name,
            'brand_code' => $this->brand_code,
            'brand_slug'=>$this->slug,
            'brand_logo' => url('/uploads/brand/'.$this->brand_logo),
            'remarks' => $this->remarks,
            'brand_followers_count'=>$this->brand_followers_count,
            'brand_sliders'=>BrandSliderResource::collection($this->brandSliders),

        ];
    }
}
