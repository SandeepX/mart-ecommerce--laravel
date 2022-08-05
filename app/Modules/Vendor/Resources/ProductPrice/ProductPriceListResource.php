<?php

namespace App\Modules\Vendor\Resources\ProductPrice;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductPriceListResource extends JsonResource
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
            "product_price_list_code" =>  $this->product_price_list_code,
            "product_variant_code" => $this->product_variant_code,
            "product_variant_name" => !is_null($this->product_variant_code) ? $this->productVariant->product_variant_name : '',
//            "mrp" => number_format($this->mrp, 2),
            "mrp" => round($this->mrp,2),
            "admin_margin_type" => $this->admin_margin_type,
            "admin_margin_value" => $this->admin_margin_value,
            "wholesale_margin_type" => $this->wholesale_margin_type,
            "wholesale_margin_value" => $this->wholesale_margin_value,
            "retail_store_margin_type" => $this->retail_store_margin_type,
            "retail_store_margin_value" => $this->retail_store_margin_value,
            'packaging_description' =>$this->packaging_description
        ];
    }
}
