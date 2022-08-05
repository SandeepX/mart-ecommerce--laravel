<?php

namespace App\Modules\Home\Resources\Slider;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
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
            "slider_url" => $this->slider_url,
            "image"      => photoToUrl($this->slider_image, asset($this->uploadFolder)),
    
        ];    
    }
}