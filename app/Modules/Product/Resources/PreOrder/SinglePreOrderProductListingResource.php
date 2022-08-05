<?php

namespace App\Modules\Product\Resources\PreOrder;
use App\Modules\AlpasalWarehouse\Models\PreOrder\PreOrderPackagingUnitDisableList;
use App\Modules\Product\Helpers\PackageQuantityConverter;
use App\Modules\Product\Models\ProductImage;
use App\Modules\Product\Models\ProductVariantImage;
use Illuminate\Http\Resources\Json\JsonResource;

class SinglePreOrderProductListingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $disabledUnitList = PreOrderPackagingUnitDisableList::where('warehouse_preorder_product_code'
            ,$this->warehouse_preorder_product_code)
            ->pluck('unit_name')->toArray();

        $packageInformation = [
            'microToUnitValue' => $this->micro_to_unit_value,
            'unitToMacroValue' => $this->unit_to_macro_value,
            'macroToSuperValue' => $this->macro_to_super_value
        ];

        $data =  [
            'warehouse_preorder_listing_code' => $this->warehouse_preorder_listing_code,
            'product_code' => $this->product_code,
            'product_name' => $this->product_name,
            'product_image' => photoToUrl($this->product_image,url(ProductImage::IMAGE_PATH)),
            'product_variant_code'=>$this->product_variant_code,
            'product_variant_name' => $this->product_variant_name,
            'product_variant_image' => isset($this->product_variant_image) ? photoToUrl($this->product_variant_image,url(ProductVariantImage::IMAGE_PATH)) : photoToUrl($this->product_image,url(ProductImage::IMAGE_PATH)),
        ];

        $packagingInformation = [];

        if($this->micro_unit_code && !in_array('micro',$disabledUnitList)){
            array_push($packagingInformation,[
                'package_name' => $this->micro_package_name,
                'package_code' => $this->micro_unit_code,
                'ordered_quantity' => isset($this->ordered_micro_quantity) ? $this->ordered_micro_quantity : 0,
                'price' => roundPrice($this->micro_price),
                'package_consists' => ''
            ]);
        }

        if($this->unit_code && !in_array('unit',$disabledUnitList)){
            $packageConsistsQuantity = PackageQuantityConverter::getConsistsQuantityofBelowPackage(
                $packageInformation,
                'unit'
            );
            array_push($packagingInformation,[
                'package_name' => $this->unit_package_name,
                'package_code' => $this->unit_code,
                'ordered_quantity' => isset($this->ordered_unit_quantity) ? $this->ordered_unit_quantity : 0,
                'price' => roundPrice($this->unit_price),
                'package_consists' => '1 '.$this->unit_package_name.' = '.$packageConsistsQuantity.' '.$this->micro_package_name
            ]);
        }

        if($this->macro_unit_code && !in_array('macro',$disabledUnitList)){
            $packageConsistsQuantity = PackageQuantityConverter::getConsistsQuantityofBelowPackage(
                $packageInformation,
                'macro'
            );
            array_push($packagingInformation,[
                'package_name' => $this->macro_package_name,
                'package_code' => $this->macro_unit_code,
                'ordered_quantity' => isset($this->ordered_macro_quantity) ? $this->ordered_macro_quantity : 0,
                'price' => roundPrice($this->macro_price),
                'package_consists' =>  '1 '.$this->macro_package_name.' = '.$packageConsistsQuantity.' '.$this->unit_package_name
            ]);
        }

        if($this->super_unit_code && !in_array('super',$disabledUnitList)){
            $packageConsistsQuantity = PackageQuantityConverter::getConsistsQuantityofBelowPackage(
                $packageInformation,
                'super'
            );
            array_push($packagingInformation,[
                'package_name' => $this->super_package_name,
                'package_code' => $this->super_unit_code,
                'ordered_quantity' => isset($this->ordered_super_quantity) ? $this->ordered_super_quantity : 0,
                'price' => roundPrice($this->super_price),
                'package_consists' =>  '1 '.$this->super_package_name.' = '.$packageConsistsQuantity.' '.$this->macro_package_name
            ]);
        }

        $data['packaging_information'] = $packagingInformation;


        return $data;
    }
}
