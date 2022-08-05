<?php


namespace App\Modules\Product\Helpers;


use App\Modules\Package\Models\PackageType;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use App\Modules\Product\Models\ProductVariant;
use Exception;
class ProductUnitPackagingHelper
{

    public static function getProductPackagingDetail($productCode)
    {

        $productVariants = ProductMaster::leftJoin('product_variants', function ($join) {
            $join->on('product_variants.product_code',
                '=',
                'products_master.product_code');
        })->leftJoin('product_packaging_details', function ($join) {
            $join->on(function($join){
                $join->on('product_packaging_details.product_code','=', 'products_master.product_code')
                    ->on(function ($q){
                    $q->on('product_packaging_details.product_variant_code','=', 'product_variants.product_variant_code')
                        ->orWhere(function($q){
                            $q->where('product_packaging_details.product_variant_code',null);
                        });
                });
            })->whereNull('product_packaging_details.deleted_at');
        })->leftJoin('package_types as unit_package_name', function ($join) {
            $join->on('unit_package_name.package_code',
                '=',
                'product_packaging_details.unit_code')->whereNull('product_packaging_details.deleted_at');
        })->leftJoin('package_types as micro_package_name', function ($join) {
            $join->on('micro_package_name.package_code',
                '=',
                'product_packaging_details.micro_unit_code')
                ->whereNull('product_packaging_details.deleted_at');
        })->leftJoin('package_types as macro_package_name', function ($join) {
            $join->on('macro_package_name.package_code',
                '=',
                'product_packaging_details.macro_unit_code')
                ->whereNull('product_packaging_details.deleted_at');
        })->leftJoin('package_types as super_package_name', function ($join) {
            $join->on('super_package_name.package_code',
                '=',
                'product_packaging_details.super_unit_code')
                ->whereNull('product_packaging_details.deleted_at');
        })->select('product_variants.product_variant_code',
            'products_master.product_code',
            'product_variants.product_variant_name',
            'product_packaging_details.product_packaging_detail_code',

            'product_packaging_details.micro_unit_code',
            'micro_package_name.id as micro_unit_id',
            'micro_package_name.package_code as micro_unit_code',
            'micro_package_name.package_name as micro_unit_name',
            'micro_package_name.remarks as micro_unit_remarks',

            'product_packaging_details.unit_code',
            'unit_package_name.id as unit_id',
            'unit_package_name.package_code as unit_code',
            'unit_package_name.package_name as unit_name',
            'unit_package_name.remarks as unit_remarks',


            'product_packaging_details.macro_unit_code',
            'macro_package_name.id as macro_unit_id',
            'macro_package_name.package_code as macro_unit_code',
            'macro_package_name.package_name as macro_unit_name',
            'macro_package_name.remarks as macro_unit_remarks',

            'product_packaging_details.super_unit_code',
            'super_package_name.id as super_unit_id',
            'super_package_name.package_code as super_unit_code',
            'super_package_name.package_name as super_unit_name',
            'super_package_name.remarks as super_unit_remarks',

            'product_packaging_details.micro_to_unit_value',
            'product_packaging_details.unit_to_macro_value',
            'product_packaging_details.macro_to_super_value'
        )
            ->where('products_master.product_code',$productCode)
            ->get();

        return $productVariants;
    }

    public static function findProductPackagingDetail($productCode,$productVariantCode=null)
    {

        $productPackageTypes =ProductUnitPackageDetail::join('package_types as unit_package_name', function ($join) {
            $join->on('unit_package_name.package_code',
                '=',
                'product_packaging_details.unit_code')
                ->whereNull('unit_package_name.deleted_at');
        })->join('package_types as micro_unit_package_name', function ($join) {
            $join->on('micro_unit_package_name.package_code',
                '=',
                'product_packaging_details.micro_unit_code')
                ->whereNull('micro_unit_package_name.deleted_at');
        })->leftJoin('package_types as macro_unit_package_name', function ($join) {
            $join->on('macro_unit_package_name.package_code',
                '=',
                'product_packaging_details.macro_unit_code')
                ->whereNull('macro_unit_package_name.deleted_at');
        })->leftJoin('package_types as super_unit_package_name', function ($join) {
            $join->on('super_unit_package_name.package_code',
                '=',
                'product_packaging_details.super_unit_code')
                ->whereNull('super_unit_package_name.deleted_at');
        })->select(
            'micro_unit_package_name.id as micro_unit_id',
            'micro_unit_package_name.package_code as micro_unit_code',
            'micro_unit_package_name.package_name as micro_unit_name',
            'micro_unit_package_name.remarks as micro_unit_remarks',

            'unit_package_name.id as unit_id',
            'unit_package_name.package_code as unit_code',
            'unit_package_name.package_name as unit_name',
            'unit_package_name.remarks as unit_remarks',

            'macro_unit_package_name.id as macro_unit_id',
            'macro_unit_package_name.package_code as macro_unit_code',
            'macro_unit_package_name.package_name as macro_unit_name',
            'macro_unit_package_name.remarks as macro_unit_remarks',

            'super_unit_package_name.id as super_unit_id',
            'super_unit_package_name.package_code as super_unit_code',
            'super_unit_package_name.package_name as super_unit_name',
            'super_unit_package_name.remarks as super_unit_remarks',

            'product_packaging_details.product_code as product_code',
            'product_packaging_details.product_variant_code as product_variant_code',
            'product_packaging_details.micro_to_unit_value as micro_to_unit_value',
            'product_packaging_details.unit_to_macro_value as unit_to_macro_value',
            'product_packaging_details.macro_to_super_value as macro_to_super_value'
        )->where('product_packaging_details.product_code',$productCode)
            ->where('product_packaging_details.product_variant_code',$productVariantCode)->first();
        return $productPackageTypes;
    }

    public static function isProductPackagedByPackageCode(
        $packageCode,$productCode,$productVariantCode=null){

        $productPackagingDetail = ProductUnitPackageDetail::where('product_code',$productCode)
            ->where('product_variant_code',$productVariantCode)
            ->where(function ($query) use ($packageCode){
                $query->where('micro_unit_code',$packageCode)
                    ->orWhere('unit_code',$packageCode)
                    ->orWhere('macro_unit_code',$packageCode)
                    ->orWhere('super_unit_code',$packageCode);
            })->first();

        if ($productPackagingDetail){
            return true;
        }

        return false;
    }



    public static function convertToMicroPackagingUnitQuantity(
        $productPackagingDetail,$quantity,$from,$to='MICRO_UNIT_TYPE')
    {
        if ($from == $to){
            return $quantity;
        }
        if ($from == 'UNIT_TYPE' && $to='MICRO_UNIT_TYPE'){
            return $productPackagingDetail->micro_to_unit_value * $quantity;
        }
        elseif ($from == 'MACRO_UNIT_TYPE' && $to='MICRO_UNIT_TYPE'){
            return $productPackagingDetail->micro_to_unit_value
                * $productPackagingDetail->unit_to_macro_value * $quantity;
        }
        elseif ($from == 'SUPER_UNIT_TYPE' && $to='MICRO_UNIT_TYPE'){
            return $productPackagingDetail->micro_to_unit_value
                * $productPackagingDetail->unit_to_macro_value
                * $productPackagingDetail->macro_to_super_value * $quantity;
        }

        throw new Exception("Unsupported conversion type.");
    }

    public static function convertToMicroUnitQuantity(
        $orderedPackageCode, $productPackagingDetail,$quantity){

            if ($productPackagingDetail->micro_unit_code == $orderedPackageCode) {
                return $quantity;
            }
            if ($productPackagingDetail->unit_code == $orderedPackageCode) {
                return $productPackagingDetail->micro_to_unit_value * $quantity;
            }
            if ($productPackagingDetail->macro_unit_code == $orderedPackageCode) {
                return $productPackagingDetail->micro_to_unit_value
                    * $productPackagingDetail->unit_to_macro_value * $quantity;
            }
            if ($productPackagingDetail->super_unit_code == $orderedPackageCode) {
                return $productPackagingDetail->micro_to_unit_value
                    * $productPackagingDetail->unit_to_macro_value
                    * $productPackagingDetail->macro_to_super_value * $quantity;
            }

        throw new Exception("Package code not matched in packaging detail.");

    }

    public static function getAvailableProductPackagingTypes($productCode,$productVariantCode=null){
        $productUnitPackagingDetail = ProductUnitPackagingHelper::findProductPackagingDetail(
            $productCode,$productVariantCode);

        if (!$productUnitPackagingDetail){
            throw new Exception('Packaging not available for the product.');
        }

        $productPackagingTypes=[];
        $productUnitPackagingDetailWithPrice = (new ProductPackagingPriceHelper())->getProductPackagingWithPrice(
            $productUnitPackagingDetail,$productCode,$productVariantCode);
        if ($productUnitPackagingDetail){
            if ($productUnitPackagingDetail->micro_unit_code){
                array_push($productPackagingTypes,[
                    'package_code'=>$productUnitPackagingDetail->micro_unit_code,
                    'package_name'=>$productUnitPackagingDetail->micro_unit_name,
                    'unit_rate' => $productUnitPackagingDetailWithPrice->micro_unit_rate,
                    'micro_qty' => 1,
                ]);
            }
            if ($productUnitPackagingDetail->unit_code){
                array_push($productPackagingTypes,[
                    'package_code'=>$productUnitPackagingDetail->unit_code,
                    'package_name'=>$productUnitPackagingDetail->unit_name,
                    'unit_rate' => $productUnitPackagingDetailWithPrice->unit_rate,
                    'micro_qty' => self::convertToMicroUnitQuantity($productUnitPackagingDetail->unit_code,
                        $productUnitPackagingDetail,1
                                                               ),
                ]);
            }
            if ($productUnitPackagingDetail->macro_unit_code){
                array_push($productPackagingTypes,[
                    'package_code'=>$productUnitPackagingDetail->macro_unit_code,
                    'package_name'=>$productUnitPackagingDetail->macro_unit_name,
                    'unit_rate' =>  $productUnitPackagingDetailWithPrice->macro_unit_rate,
                    'micro_qty' => self::convertToMicroUnitQuantity($productUnitPackagingDetail->macro_unit_code,
                        $productUnitPackagingDetail,1
                    ),
                ]);
            }
            if ($productUnitPackagingDetail->super_unit_code){
                array_push($productPackagingTypes,[
                    'package_code'=>$productUnitPackagingDetail->super_unit_code,
                    'package_name'=>$productUnitPackagingDetail->super_unit_name,
                    'unit_rate' =>  $productUnitPackagingDetailWithPrice->super_unit_rate,
                    'micro_qty' =>  self::convertToMicroUnitQuantity($productUnitPackagingDetail->super_unit_code,
                        $productUnitPackagingDetail,1
                    ),
                ]);
            }

        }

       // dd($productPackagingTypes);
        return $productPackagingTypes;
    }

}
