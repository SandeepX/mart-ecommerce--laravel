<?php

namespace App\Modules\Product\Resources\ProductCollection;

use Illuminate\Http\Resources\Json\JsonResource;

class MinimalProductCollectionResource extends JsonResource
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
            'product_collection_title' => $this->product_collection_title,
            'product_collection_slug' => $this->product_collection_slug,
            'product_collection_subtitle' => $this->product_collection_subtitle,
            'product_collection_image' => photoToUrl($this->product_collection_image,asset($this->uploadFolder)),
            'products_count' => ($this->activeProducts)->count()
        ];
    }
}
