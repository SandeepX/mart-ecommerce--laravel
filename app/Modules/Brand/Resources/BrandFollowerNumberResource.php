<?php

namespace App\Modules\Brand\Resources;



use App\Modules\Brand\Models\BrandSlider;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandFollowerNumberResource extends JsonResource
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
            'message'=>$this['message'],
            'follow'=>$this['follow'],
            'followers' => $this['data'],
        ];
    }
    public function with($request)
    {
        return [
            'error' => false,
            'code' => 200
        ];
    }
}
