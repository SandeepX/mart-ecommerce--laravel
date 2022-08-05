<?php


namespace App\Modules\Product\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ProductPackagingDetailResource extends JsonResource
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
            "product_code" =>  $this->product_code,
            "product_variant_code" => $this->product_variant_code,
            "product_variant_name" =>  $this->productVariant->product_variant_name,
            "product_packaging_detail_code" =>  $this->product_packaging_detail_code,
            "micro_unit_code" => $this->micro_unit_code,
            "micro_unit_name" => $this->microPackageType->package_name,
            "unit_code" => $this->unit_code,
            "unit_name" => $this->unitPackageType->package_name,
            "macro_unit_code" => $this->macro_unit_code,
            "macro_unit_name" => $this->macroPackageType->package_name ?? null,
            "super_unit_code" => $this->super_unit_code,
            "super_unit_name" => $this->superPackageType->package_name  ?? null,
            "micro_to_unit_value" => $this->micro_to_unit_value,
            "unit_to_macro_value" => $this->unit_to_macro_value,
            "macro_to_super_value" => $this->macro_to_super_value
        ];
    }
}
