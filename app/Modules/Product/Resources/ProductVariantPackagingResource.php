<?php


namespace App\Modules\Product\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantPackagingResource extends JsonResource
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
            "product_variant_name" =>  $this->product_variant_name,
            "product_packaging_detail_code" =>  $this->product_packaging_detail_code,
            "micro_unit_code" => $this->micro_unit_code,
            "micro_unit_name" => $this->micro_unit_name,
            "micro_unit" => $this->micro_unit_code ?[
                'id' => $this->micro_unit_id,
                'package_code' => $this->micro_unit_code,
                'package_name' => $this->micro_unit_name,
                'remarks' => $this->micro_unit_remarks,
            ]:null,
            "unit_code" => $this->unit_code,
            "unit_name" => $this->unit_name,
            "unit" => $this->unit_code ? [
                'id' => $this->unit_id,
                'package_code' => $this->unit_code,
                'package_name' => $this->unit_name,
                'remarks' => $this->unit_remarks,
            ] : null,

            "macro_unit_code" => $this->macro_unit_code,
            "macro_unit_name" => $this->macro_unit_name,
            "macro_unit" => $this->macro_unit_code ? [
                'id' => $this->macro_unit_id,
                'package_code' => $this->macro_unit_code,
                'package_name' => $this->macro_unit_name,
                'remarks' => $this->macro_unit_remarks,
            ] : null,

            "super_unit_code" => $this->super_unit_code,
            "super_unit_name" => $this->super_unit_name,
            "super_unit" => $this->super_unit_code ? [
                'id' => $this->super_unit_id,
                'package_code' => $this->super_unit_code,
                'package_name' => $this->super_unit_name,
                'remarks' => $this->super_unit_remarks,
            ]:null,

            "micro_to_unit_value" =>(int)$this->micro_to_unit_value,
            "unit_to_macro_value" => (int)$this->unit_to_macro_value,
            "macro_to_super_value" => (int)$this->macro_to_super_value
        ];
    }
}
