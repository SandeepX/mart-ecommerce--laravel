<?php

namespace App\Modules\Brand\Resources;



use App\Modules\Brand\Models\BrandSlider;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandSliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'image' => (isset($this->image))?photoToUrl($this->image,url(BrandSlider::BRAND_SLIDER_IMAGE_PATH)): NULL,
            'description' =>(isset($this->description))?$this->description:NULL,
        ];
    }
}
