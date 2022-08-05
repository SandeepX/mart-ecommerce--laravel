<?php


namespace App\Modules\AlpasalWarehouse\Resources;


use App\Modules\Product\Helpers\ProductPriceHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehousePreOrderRelatedProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $price='N/A';
        if ($this->isPriceDisplayable()){
            $price=(new ProductPriceHelper())->getPreOrderProductStorePriceRange($this->warehouse_preorder_listing_code,$this->product_code);
        }
        return [
            'warehouse_preorder_product_code' => $this->warehouse_preorder_product_code,
            'warehouse_preorder_listing_code' => $this->warehouse_preorder_listing_code,
            'product_code'=> $this->product_code,
            //'product_variant_code'=> $this->product_variant_code,
            'product_name'=> $this->product->product_name,
            'slug'=> $this->product->slug,
            //'product_variant_name'=> $this->productVariant? $this->productVariant->product_variant_name : null,
            'featured_image' => $this->product->getFeaturedImage(),
            'price' =>$price,
            'category'=> $this->product->category->category_name,
            'brand'=> $this->product->brand->brand_name,
            'can_pre_order'=>$this->warehousePreOrderListing->isPreOrderable()
        ];
    }
}
