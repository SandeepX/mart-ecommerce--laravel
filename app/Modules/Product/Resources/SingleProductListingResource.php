<?php

namespace App\Modules\Product\Resources;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductPackagingUnitDisableList;
use App\Modules\Product\Helpers\PackageQuantityConverter;
use App\Modules\Product\Models\ProductImage;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Models\ProductVariantImage;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleProductListingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $disabledUnitList = WarehouseProductPackagingUnitDisableList::where(
            'warehouse_product_master_code',$this->warehouse_product_master_code)
            ->pluck('unit_name')->toArray();

        //dd($disabledUnitList);

        $packageInformation = [
            'microToUnitValue' => $this->micro_to_unit_value,
            'unitToMacroValue' => $this->unit_to_macro_value,
            'macroToSuperValue' => $this->macro_to_super_value
        ];

        $data =  [
            'product_code' => $this->product_code,
            'product_name' => $this->product_name,
            'product_image' => photoToUrl($this->product_image,url(ProductImage::IMAGE_PATH)),
            'product_variant_code'=>$this->product_variant_code,
            'product_variant_name' => $this->product_variant_name,
            'product_variant_image' => isset($this->product_variant_image) ? photoToUrl($this->product_variant_image,url(ProductVariantImage::IMAGE_PATH)) : photoToUrl($this->product_image,url(ProductImage::IMAGE_PATH)),
            'current_stock' => (int)$this->current_stock,
        ];

        $packagingInformation = [];

        if($this->micro_unit_code && !in_array('micro', $disabledUnitList)){
            array_push($packagingInformation,[
                'package_name' => $this->micro_package_name,
                'package_code' => $this->micro_unit_code,
                'cart_quantity' => isset($this->carts_micro_quantity) ? $this->carts_micro_quantity : 0,
                'cartable_stock' => (int)$this->cartable_micro_stock,
                'price' => roundPrice($this->micro_price),
                 'package_consists' => ''
            ]);
        }

        if($this->unit_code && !in_array('unit', $disabledUnitList)){
            $packageConsistsQuantity = PackageQuantityConverter::getConsistsQuantityofBelowPackage(
                $packageInformation,
                'unit'
            );
            array_push($packagingInformation,[
                'package_name' => $this->unit_package_name,
                'package_code' => $this->unit_code,
                'cart_quantity' => isset($this->carts_unit_quantity) ? $this->carts_unit_quantity : 0,
                'cartable_stock' => (int)$this->cartable_unit_stock,
                'price' => roundPrice($this->unit_price),
                'package_consists' => '1 '.$this->unit_package_name.' = '.$packageConsistsQuantity.' '.$this->micro_package_name
            ]);
        }

        if($this->macro_unit_code && !in_array('macro', $disabledUnitList)){
            $packageConsistsQuantity = PackageQuantityConverter::getConsistsQuantityofBelowPackage(
                $packageInformation,
                'macro'
            );
            array_push($packagingInformation,[
                'package_name' => $this->macro_package_name,
                'package_code' => $this->macro_unit_code,
                'cart_quantity' => isset($this->carts_macro_quantity) ? $this->carts_macro_quantity : 0,
                'cartable_stock' => (int)$this->cartable_macro_stock,
                'price' => roundPrice($this->macro_price),
                'package_consists' => '1 '.$this->macro_package_name.' = '.$packageConsistsQuantity.' '.$this->unit_package_name
            ]);
        }

        if($this->super_unit_code && !in_array('super', $disabledUnitList)){
            $packageConsistsQuantity = PackageQuantityConverter::getConsistsQuantityofBelowPackage(
                $packageInformation,
                'super'
            );
            array_push($packagingInformation,[
                'package_name' => $this->super_package_name,
                'package_code' => $this->super_unit_code,
                'cart_quantity' => isset($this->carts_super_quantity) ? $this->carts_super_quantity : 0,
                'cartable_stock' => (int)$this->cartable_super_stock,
                'price' => roundPrice($this->super_price),
                'package_consists' => '1 '.$this->super_package_name.' = '.$packageConsistsQuantity.' '.$this->macro_package_name
            ]);
        }

        $data['packaging_information'] = $packagingInformation;


        return $data;
    }
}
