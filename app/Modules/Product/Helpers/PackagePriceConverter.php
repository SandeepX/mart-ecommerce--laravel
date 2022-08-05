<?php

namespace App\Modules\Product\Helpers;

class PackagePriceConverter
{

    public static function packageWisePriceFromMicroCalculator(
        $conversionValues,
        $convertInPackageName,
        $microPrice
    )
    {
        $convertedPackagePrice = 0;
        if($convertInPackageName == 'micro') {
            $convertedPackagePrice =  $microPrice;
        }
        elseif($convertInPackageName == 'unit') {
            $convertedPackagePrice = $microPrice
                 * $conversionValues['microToUnitValue'];
        }
        elseif($convertInPackageName == 'macro') {
            $convertedPackagePrice =   $microPrice
                * $conversionValues['microToUnitValue']
                *  $conversionValues['unitToMacroValue'];
        }
        elseif($convertInPackageName == 'super'){
            $convertedPackagePrice =  $microPrice
                    * $conversionValues['microToUnitValue']
                    * $conversionValues['unitToMacroValue']
                    * $conversionValues['macroToSuperValue'];
        }

        return $convertedPackagePrice;
    }



}
