<?php

namespace App\Modules\Store\Resources\StorePreOrder;
use App\Modules\Brand\Resources\BrandResource;
use App\Modules\Category\Resources\CategoryResource;
use App\Modules\Product\Helpers\ProductPriceHelper;
use App\Modules\Product\Resources\ProductImageResource;
use App\Modules\Product\Resources\ProductPackageResource;
use App\Modules\Product\Resources\ProductSensitivity\ProductSensitivityResource;
use App\Modules\Product\Resources\ProductVariantResource;
use App\Modules\Product\Resources\ProductWarranty\ProductWarrantyDetailResource;
use App\Modules\Store\Helpers\PreOrder\PreOrderVariantSelectionHelper;
use App\Modules\Vendor\Resources\ProductPrice\ProductPriceListResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SinglePreOrderProductResourceForFrontend extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $hasProductPackagingDetail = $this->product_packaging_detail? true:false;


        $singleProductResource= [
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
            'product_variant_info' => $this->getMainVariantsInProduct(),
            'first_variant' => $this->first_variant,
            'remarks' => $this->remarks,
            'video_link' => $this->video_link == null ? '' : 'https://www.youtube.com/embed/'.$this->video_link ,
            'highlights' => json_decode($this->highlights),
            'variant_tag' => $this->hasVariants(),
            //'category_type_code' => $this->category_type_code,
           // 'sku' => $this->sku,
            'rate' =>($this->rate),
            'is_taxable' => $this->is_taxable,
            'can_pre_order' => $this->can_pre_order,
            'has_start_time_past'=>$this->has_start_time_past,
            'store_pre_order_code' =>$this->store_pre_order_code
            //'warehouse_product_stock'=> $this->warehouse_product_stock,
            //'rating'=>$this->rating
        ];

        $singleProductResource['product_packaging_types'] =[];
        if ($hasProductPackagingDetail){
            if ($this->product_packaging_detail->micro_unit_code && !in_array('micro',$this->disabled_unit_list)){
                array_push($singleProductResource['product_packaging_types'],[
                    'package_code'=>$this->product_packaging_detail->micro_unit_code,
                    'package_name'=>$this->product_packaging_detail->micro_unit_name,
                    'price' =>$this->rate,
                    'description' => ''
                ]);
            }
            if ($this->product_packaging_detail->unit_code && !in_array('unit',$this->disabled_unit_list)){
                if ($this->rate != 'N/A'){
                    $price =roundPrice($this->product_packaging_detail->micro_to_unit_value *$this->rate);
                }
                else{
                    $price= $this->rate;
                }
                array_push($singleProductResource['product_packaging_types'],[
                    'package_code'=>$this->product_packaging_detail->unit_code,
                    'package_name'=>$this->product_packaging_detail->unit_name,
                    'price' => $price,
                    'description' => 'One '. $this->product_packaging_detail->unit_name. ' consists '.
                        $this->product_packaging_detail->micro_to_unit_value. ' '.
                        $this->product_packaging_detail->micro_unit_name
                ]);
            }
            if ($this->product_packaging_detail->macro_unit_code && !in_array('macro',$this->disabled_unit_list)){
                if ($this->rate != 'N/A'){
                    $price =roundPrice($this->product_packaging_detail->micro_to_unit_value *
                        $this->product_packaging_detail->unit_to_macro_value *$this->rate);
                }
                else{
                    $price= $this->rate;
                }
                array_push($singleProductResource['product_packaging_types'],[
                    'package_code'=>$this->product_packaging_detail->macro_unit_code,
                    'package_name'=>$this->product_packaging_detail->macro_unit_name,
                    'price' =>$price,
                    'description' => 'One '. $this->product_packaging_detail->macro_unit_name. ' consists '.
                        $this->product_packaging_detail->micro_to_unit_value*
                        $this->product_packaging_detail->unit_to_macro_value. ' '.
                        $this->product_packaging_detail->micro_unit_name
                ]);
            }
            if ($this->product_packaging_detail->super_unit_code && !in_array('super',$this->disabled_unit_list)){
                if ($this->rate != 'N/A'){
                    $price =roundPrice($this->product_packaging_detail->micro_to_unit_value *
                        $this->product_packaging_detail->unit_to_macro_value *
                        $this->product_packaging_detail->macro_to_super_value*$this->rate);
                }
                else{
                    $price= $this->rate;
                }
                array_push($singleProductResource['product_packaging_types'],[
                    'package_code'=>$this->product_packaging_detail->super_unit_code,
                    'package_name'=>$this->product_packaging_detail->super_unit_name,
                    'price' =>$price,
                    'description' => 'One '. $this->product_packaging_detail->super_unit_name. ' consists '.
                        $this->product_packaging_detail->micro_to_unit_value*
                        $this->product_packaging_detail->unit_to_macro_value *
                        $this->product_packaging_detail->macro_to_super_value.' '.
                        $this->product_packaging_detail->micro_unit_name
                ]);
            }

        }

        return $singleProductResource;
    }
}
