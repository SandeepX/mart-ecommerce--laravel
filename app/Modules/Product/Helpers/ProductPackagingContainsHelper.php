<?php


namespace App\Modules\Product\Helpers;


use App\Modules\Product\Models\ProductPackagingHistory;

class ProductPackagingContainsHelper
{

    public static function findProductPackagingDetailByPPHCode($pph_code)
    {
        return ProductPackagingHistory::where('product_packaging_history_code', $pph_code)
            ->first();
    }

    public static function getProductPackagingContainsByPackagingHistroy($productCode,$pvCode=null)
    {
        $productPackagingDetail = ProductPackagingHistory::where('product_code', $productCode)
            ->where('product_variant_code', $pvCode)
            ->orderBy('product_packaging_history.id', 'DESC')
            ->get();

        if (!$productPackagingDetail || count($productPackagingDetail)==0) {
            return [];
        }
        $packagingInfo = [];
        foreach ($productPackagingDetail as $key => $value){
            $packagingInfo[$key]['pph_code'] = $value->product_packaging_history_code;
            $packagingInfo[$key]['packaging'] = self::constructPackagingContains($value);
        }
        return  $packagingInfo;
    }

    public static function getProductPackagingContainsByPPHCode($pph_code)
    {
        $productPackagingDetail = self::findProductPackagingDetailByPPHCode($pph_code);
        if (!$productPackagingDetail) {
            return [];
        }
        $packagingInfo = self::constructPackagingContains($productPackagingDetail);
        return  $packagingInfo;
    }

    private static function constructPackagingContains($productPackagingDetail)
    {
        if(!$productPackagingDetail){
            return  [];
        }
        $packagingInfo=[];
        if ($productPackagingDetail->super_unit_code){
            $toBePushed = '1 ' . $productPackagingDetail['superPackageType']['package_name'] . ' = ' .
                $productPackagingDetail->macro_to_super_value . ' ' .
                $productPackagingDetail['macroPackageType']['package_name'].'';

            $toBePushed=$toBePushed.'(1 ' . $productPackagingDetail['superPackageType']['package_name'] . ' = ' .
                $productPackagingDetail->unit_to_macro_value *
                $productPackagingDetail->macro_to_super_value . ' ' .
                $productPackagingDetail['unitPackageType']['package_name'].') ';

            $toBePushed=$toBePushed.'(1 ' .$productPackagingDetail['superPackageType']['package_name'] . ' = ' .
                $productPackagingDetail->micro_to_unit_value *
                $productPackagingDetail->unit_to_macro_value *
                $productPackagingDetail->macro_to_super_value . ' ' .
                $productPackagingDetail['microPackageType']['package_name'].')';
            array_push($packagingInfo,$toBePushed);
        }

        if ($productPackagingDetail->macro_unit_code){
            $toBePushed = '1 ' . $productPackagingDetail['macroPackageType']['package_name'] . ' = ' .
                $productPackagingDetail->unit_to_macro_value . ' ' .
                $productPackagingDetail['unitPackageType']['package_name'].'';

            $toBePushed=$toBePushed.'(1 ' . $productPackagingDetail['macroPackageType']['package_name'] . ' = ' .
                $productPackagingDetail->micro_to_unit_value *
                $productPackagingDetail->unit_to_macro_value . ' ' .
                $productPackagingDetail['microPackageType']['package_name'].')';

            array_push($packagingInfo,$toBePushed);
        }

        if ($productPackagingDetail->unit_code){
            $toBePushed='1 ' . $productPackagingDetail['unitPackageType']['package_name'] . ' = ' .
                $productPackagingDetail->micro_to_unit_value. ' ' .
                $productPackagingDetail['microPackageType']['package_name'];
            array_push($packagingInfo,$toBePushed);
        }

        return  $packagingInfo;
    }



}
