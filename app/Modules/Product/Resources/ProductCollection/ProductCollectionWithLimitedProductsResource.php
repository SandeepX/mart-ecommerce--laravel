<?php

namespace App\Modules\Product\Resources\ProductCollection;

use App\Modules\Product\Resources\MinimalProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCollectionWithLimitedProductsResource extends JsonResource
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
           // 'products_count' => $this->products_count,
            'products_count' => count($this->products()->wherePivot('is_active',1)->qualifiedToDisplay()->get()),
            'products' => $this->products()->qualifiedToDisplay()->wherePivot('is_active', 1)->limit(8)->get()->map(function ($product) {
                      return new MinimalProductResource($product);
            })
            // 'products' => $this->products->map(function ($product) {
            //     return new MinimalProductResource($product);
            // })


        ];





















    }
}
