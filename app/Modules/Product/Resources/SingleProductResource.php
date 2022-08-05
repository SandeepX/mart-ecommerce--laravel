<?php

namespace App\Modules\Product\Resources;
use App\Modules\Brand\Resources\BrandResource;
use App\Modules\Category\Resources\CategoryResource;
use App\Modules\Product\Resources\ProductSensitivity\ProductSensitivityResource;
use App\Modules\Product\Resources\ProductWarranty\ProductWarrantyDetailResource;
use App\Modules\Vendor\Resources\ProductPrice\ProductPriceListResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleProductResource extends JsonResource
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
            'description' => $this->description,
            'brand' => new BrandResource($this->brand),
            'category' => new CategoryResource($this->category),
            'sensitivity' => new ProductSensitivityResource($this->sensitivity),
            'warranty' => new ProductWarrantyDetailResource($this->warrantyDetail),
            //'package' => new ProductPackageResource($this->package),
            'product_images' => ProductImageResource::collection($this->images),
            'product_variants' => ProductVariantResource::collection($this->productVariants),
            'product_variant_info' => $this->getVariantInfoByProduct(),
            'remarks' => $this->remarks,
            'video_link' =>$this->video_link == null ? '' : 'https://www.youtube.com/watch?v='.$this->video_link ,
            'video_embed_link' =>$this->video_link == null ? '' : 'https://www.youtube.com/embed/'.$this->video_link ,
            'highlights' => json_decode($this->highlights),
            'variant_tag' => $this->hasVariants(), // true or false
            'category_type_code' => $this->category_type_code,
            'sku' => $this->sku,
            'is_taxable' => $this->isTaxable() ? 1 : 0,
            'price_listing' => ProductPriceListResource::collection($this->priceList),
            'is_active' => $this->isActive()
        ];
    }
}
