<?php

namespace App\Modules\AlpasalWarehouse\Resources;

use App\Modules\Product\Resources\ProductListResource;
use App\Modules\Product\Resources\ProductVariantResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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
            'order_product_detail_code' => $this->order_product_detail_code,
            'product' => new ProductListResource($this->product),
            'product_variant' => new ProductVariantResource($this->productVariant),
            'package_quantity' => $this->package_quantity,
        ];
    }
}
