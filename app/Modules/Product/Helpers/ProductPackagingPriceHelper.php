<?php


namespace App\Modules\Product\Helpers;


use App\Modules\Product\Models\ProductUnitPackageDetail;

use Exception;
class ProductPackagingPriceHelper
{

    public function getProductPackagingWithPrice(
        $productPackagingDetail, $productCode, $productVariantCode = null)
    {
        $unitPrice = (new ProductPriceHelper())->getNewProductWarehousePrice($productCode, $productVariantCode);
        if ($unitPrice) {
            if ($productPackagingDetail) {
                if ($productPackagingDetail->micro_unit_code) {
                    $productPackagingDetail->micro_unit_rate = $unitPrice;
                }
                if ($productPackagingDetail->unit_code) {
                    $productPackagingDetail->unit_rate = $unitPrice * (float)$productPackagingDetail->micro_to_unit_value;
                }
                if ($productPackagingDetail->macro_unit_code) {
                    $productPackagingDetail->macro_unit_rate = $unitPrice * (float)$productPackagingDetail->micro_to_unit_value
                        * (float)$productPackagingDetail->unit_to_macro_value;
                }
                if ($productPackagingDetail->super_unit_code) {
                    $productPackagingDetail->super_unit_rate = $unitPrice * (float)$productPackagingDetail->micro_to_unit_value
                        * (float)$productPackagingDetail->unit_to_macro_value * (float)$productPackagingDetail->macro_to_super_value;
                }
            }
        }

        return $productPackagingDetail;
    }

    public function calculateProductPackagePrice(
       $orderedPackageCode, $productPackagingDetail, $productCode, $productVariantCode = null){

        $unitPrice = (new ProductPriceHelper())->getNewProductWarehousePrice($productCode, $productVariantCode);
        if ($unitPrice) {
            if ($productPackagingDetail->micro_unit_code == $orderedPackageCode) {
               return $unitPrice;
            }
            if ($productPackagingDetail->unit_code == $orderedPackageCode) {
                return $unitPrice * (float)$productPackagingDetail->micro_to_unit_value;
            }
            if ($productPackagingDetail->macro_unit_code == $orderedPackageCode) {
               return $unitPrice * (float)$productPackagingDetail->micro_to_unit_value
                    * (float)$productPackagingDetail->unit_to_macro_value;
            }
            if ($productPackagingDetail->super_unit_code == $orderedPackageCode) {
               return $unitPrice * (float)$productPackagingDetail->micro_to_unit_value
                    * (float)$productPackagingDetail->unit_to_macro_value * (float)$productPackagingDetail->macro_to_super_value;
            }
        }

    }

    public function getProductPackagingPriceByPackageCode(
        $packageCode,$productCode,$productVariantCode){
        $productPackagingDetail = ProductUnitPackageDetail::where('product_code',$productCode)
            ->where('product_variant_code',$productVariantCode)
            ->where(function ($query) use ($packageCode){
                $query->where('micro_unit_code',$packageCode)
                    ->orWhere('unit_code',$packageCode)
                    ->orWhere('macro_unit_code',$packageCode)
                    ->orWhere('super_unit_code',$packageCode);
            })->first();

        if (!$productPackagingDetail){
            throw new Exception('Packaging Detail not found for the product');
        }
    }
}
