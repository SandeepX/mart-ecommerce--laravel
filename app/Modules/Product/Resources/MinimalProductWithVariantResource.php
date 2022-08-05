<?php

namespace App\Modules\Product\Resources;
use App\Modules\Brand\Resources\BrandResource;
use App\Modules\Category\Resources\CategoryResource;
use App\Modules\Product\Resources\ProductSensitivity\ProductSensitivityResource;
use App\Modules\Product\Resources\ProductWarranty\ProductWarrantyDetailResource;
use App\Modules\Vendor\Resources\ProductPrice\ProductPriceListResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MinimalProductWithVariantResource extends JsonResource
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
            'product_code' => $this->product_code,
            'product_name' => $this->product_name,
            'slug' => $this->slug,
            'product_variants' => $this->productVariants->map(function($productVariant){
               return [
                   'product_variant_name' => $productVariant->product_variant_name,
                   'product_variant_code' => $productVariant->product_variant_code
               ];
            }),
        ];
    }
}
