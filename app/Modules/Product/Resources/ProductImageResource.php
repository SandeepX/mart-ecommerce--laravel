<?php

namespace App\Modules\Product\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource
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
            'product_image_code' => $this->product_image_code,
            'image' => url('/uploads/products/'.$this->image),
        ];
    }
}
