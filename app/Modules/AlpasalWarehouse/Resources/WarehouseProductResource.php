<?php

namespace App\Modules\AlpasalWarehouse\Resources;

use App\Modules\Product\Helpers\ProductPriceHelper;
use App\Modules\Product\Models\ProductMaster;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseProductResource extends JsonResource
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
            'featured_image' => $this->getFeaturedImage(),
            'price' => (new ProductPriceHelper())->getProductStorePriceRange($this->product_code),
            'highlights' => json_decode($this->highlights),
            'brand' => $this->brand->brand_name,
            'category'=>$this->category->category_name

        ];
    }
}
