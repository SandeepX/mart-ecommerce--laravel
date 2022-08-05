<?php


namespace App\Modules\Product\Resources;


use App\Modules\AlpasalWarehouse\Models\WarehouseProductPackagingUnitDisableList;
use App\Modules\Product\Helpers\PackageQuantityConverter;
use App\Modules\Product\Models\ProductImage;
use App\Modules\Product\Models\ProductVariantImage;
use Illuminate\Http\Resources\Json\JsonResource;

class AllWarehouseProductsListingResource extends JsonResource
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
            'product_code' => $this[0]->product_code,
            'product_name' => $this[0]->product_name,
            'product_slug' => $this[0]->product_slug,
            'product_image' => photoToUrl($this[0]->product_image,url(ProductImage::IMAGE_PATH)),
            'has_variants' => $this[0]->product_variant_code ? true :false,
            'product_variants' => $this->setProductVariants($this)
        ];


    }

    private function setProductVariants($productDetails){
        $data = [];

        foreach($productDetails->resource as $key => $productDetail){
            $disabledUnitList = [];
            $disabledUnitList = WarehouseProductPackagingUnitDisableList::where(
                'warehouse_product_master_code',$productDetail->warehouse_product_master_code)
                ->pluck('unit_name')->toArray();


            $packageInformation = [
                'microToUnitValue' => $productDetail->micro_to_unit_value,
                'unitToMacroValue' => $productDetail->unit_to_macro_value,
                'macroToSuperValue' => $productDetail->macro_to_super_value
            ];

            $data[$key] =  [
//                'product_code' => $productDetail->product_code,
//                'product_slug' => $productDetail->product_slug,
//                'product_name' => $productDetail->product_name,
//                'product_image' => photoToUrl($productDetail->product_image,url(ProductImage::IMAGE_PATH)),
                'product_variant_code'=>$productDetail->product_variant_code,
                'product_variant_name' => $productDetail->product_variant_name,
                'product_variant_image' => isset($productDetail->product_variant_image) ? photoToUrl($productDetail->product_variant_image,url(ProductVariantImage::IMAGE_PATH)) : photoToUrl($productDetail->product_image,url(ProductImage::IMAGE_PATH)),
                'current_stock' => (int)$productDetail->current_stock,
            ];

            $packagingInformation = [];

            if($productDetail->micro_unit_code && !in_array('micro', $disabledUnitList)){
                array_push($packagingInformation,[
                    'package_name' => $productDetail->micro_package_name,
                    'package_code' => $productDetail->micro_unit_code,
                    'cart_quantity' => isset($productDetail->carts_micro_quantity) ? $productDetail->carts_micro_quantity : 0,
                    'cartable_stock' => (int)$productDetail->cartable_micro_stock,
                    'price' => roundPrice($productDetail->micro_price),
                    'quantity' => 1,
                    'package_consists' => ''
                ]);
            }

            if($productDetail->unit_code && !in_array('unit', $disabledUnitList)){
                $packageConsistsQuantity = PackageQuantityConverter::getConsistsQuantityofBelowPackage(
                    $packageInformation,
                    'unit'
                );
                array_push($packagingInformation,[
                    'package_name' => $productDetail->unit_package_name,
                    'package_code' => $productDetail->unit_code,
                    'cart_quantity' => isset($productDetail->carts_unit_quantity) ? $productDetail->carts_unit_quantity : 0,
                    'cartable_stock' => (int)$productDetail->cartable_unit_stock,
                    'price' => roundPrice($productDetail->unit_price),
                    'quantity' => 1,
                    'package_consists' => '1 '.$productDetail->unit_package_name.' = '.$packageConsistsQuantity.' '.$productDetail->micro_package_name
                ]);
            }

            if($productDetail->macro_unit_code && !in_array('macro', $disabledUnitList)){
                $packageConsistsQuantity = PackageQuantityConverter::getConsistsQuantityofBelowPackage(
                    $packageInformation,
                    'macro'
                );
                array_push($packagingInformation,[
                    'package_name' => $productDetail->macro_package_name,
                    'package_code' => $productDetail->macro_unit_code,
                    'cart_quantity' => isset($productDetail->carts_macro_quantity) ? $productDetail->carts_macro_quantity : 0,
                    'cartable_stock' => (int)$productDetail->cartable_macro_stock,
                    'price' => roundPrice($productDetail->macro_price),
                    'quantity' => 1,
                    'package_consists' => '1 '.$productDetail->macro_package_name.' = '.$packageConsistsQuantity.' '.$productDetail->unit_package_name
                ]);
            }

            if($productDetail->super_unit_code && !in_array('super', $disabledUnitList)){
                $packageConsistsQuantity = PackageQuantityConverter::getConsistsQuantityofBelowPackage(
                    $packageInformation,
                    'super'
                );
                array_push($packagingInformation,[
                    'package_name' => $productDetail->super_package_name,
                    'package_code' => $productDetail->super_unit_code,
                    'cart_quantity' => isset($productDetail->carts_super_quantity) ? $productDetail->carts_super_quantity : 0,
                    'cartable_stock' => (int)$productDetail->cartable_super_stock,
                    'price' => roundPrice($productDetail->super_price),
                    'quantity' => 1,
                    'package_consists' => '1 '.$productDetail->super_package_name.' = '.$packageConsistsQuantity.' '.$productDetail->macro_package_name
                ]);
            }

            $data[$key]['packaging_information'] = $packagingInformation;

        }

        return $data;

    }

}
