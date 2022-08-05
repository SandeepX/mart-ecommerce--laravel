<?php

namespace App\Modules\Product\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use PHPUnit\Framework\Constraint\IsFalse;

class ProductListResource extends JsonResource
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
            'is_taxable' => $this->is_taxable,
            'brand_name' => $this->brand->brand_name,
            'category' => $this->category->category_name,
           // 'package_type' => isset($this->package->packageType) ? $this->package->packageType->package_name : '',
            //'units_per_package' => $this->package->units_per_package,
            'variant_tag' => $this->hasVariants(),
            'featured_image' => $this->getFeaturedImage(),
            'created_at' => $this->created_at,
            'has_price' => count($this->priceList) > 0 ? true : false,
            'is_active' => $this->is_active

        ];
    }
}
