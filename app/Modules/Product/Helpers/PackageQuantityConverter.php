<?php

namespace App\Modules\Product\Helpers;

class PackageQuantityConverter
{
    public static function packageWiseQtyFromMicroCalculator(
        $conversionValues,
        $convertInPackageName,
        $microQuantity
    )
    {
         $convertedPackageQty = 0;
         if($convertInPackageName == 'micro') {
            $convertedPackageQty =intval($microQuantity);
         }
         elseif($convertInPackageName == 'unit') {
             $convertedPackageQty = intval($microQuantity/$conversionValues['microToUnitValue']);
         }
        elseif($convertInPackageName == 'macro') {
             $convertedPackageQty = intval(  $microQuantity
                 / (
                     $conversionValues['microToUnitValue']
                     *  $conversionValues['unitToMacroValue'])
             );
         }
        elseif($convertInPackageName == 'super'){
             $convertedPackageQty = intval( $microQuantity
                 / (
                     $conversionValues['microToUnitValue']
                     * $conversionValues['unitToMacroValue']
                     * $conversionValues['macroToSuperValue'])
             );
        }
          return $convertedPackageQty;
    }

    public static function packageWiseQtyToMicroCalculator(
        $conversionValues,
        $convertInPackageName,
        $quantity
    )
    {

     $convertedPackageQty =0;

    if ($convertInPackageName == 'micro'){
            $convertedPackageQty = intval($quantity);
    }
    elseif ($convertInPackageName == 'unit'){
            $convertedPackageQty = intval($quantity
                                * $conversionValues['microToUnitValue']);
    }
    elseif ($convertInPackageName == 'macro') {
            $convertedPackageQty = intval($quantity
                                * $conversionValues['microToUnitValue']
                                * $conversionValues['unitToMacroValue']);
    } elseif($convertInPackageName == 'super'){
            $convertedPackageQty = intval($quantity
                                 * $conversionValues['microToUnitValue']
                                 * $conversionValues['unitToMacroValue']
                                 * $conversionValues['macroToSuperValue']);
    }

    return $convertedPackageQty;
    }

    public static function getConsistsQuantityofBelowPackage($conversionValues,$convertInPackageName){
        $quantityFromBelowPackage =0;

        if ($convertInPackageName == 'micro'){
            $quantityFromBelowPackage = 1;
        } elseif ($convertInPackageName == 'unit'){
            $quantityFromBelowPackage = $conversionValues['microToUnitValue'];
        } elseif ($convertInPackageName == 'macro') {
            $quantityFromBelowPackage =  $conversionValues['unitToMacroValue'];
        } elseif($convertInPackageName == 'super'){
            $quantityFromBelowPackage = $conversionValues['macroToSuperValue'];
        }

        return intval($quantityFromBelowPackage);
    }


}
