<?php


namespace App\Modules\Product\Utilities;


class ProductPackagingFormatter
{
    public function formatPackagingCombination($microQuantity,array $productPackagings){
       /* $condition1 = array(
            74250 =>  'bora',//bora //microValue=>'packagename'
            1350 =>  'catoon',//catoon
            30  =>  'packets',//packets
            1 =>  'pcs'//pcs
        );*/
        $finalPackages =[];

        $convertedMicroQty = intval($microQuantity);
        foreach( $productPackagings as $value => $packageName )
        {
            $remainingQuantity = $convertedMicroQty%$value;//remainder
            $packageQuantity = intval($convertedMicroQty/$value); //QUOTIENT
            if ($remainingQuantity == 0){
                $finalPackages[$packageName] = $packageQuantity;
                break;
            }
            if ($packageQuantity >= 1){
                $finalPackages[$packageName] = $packageQuantity;

                $convertedMicroQty = $remainingQuantity;
            }
        }

        array_walk($finalPackages, function (&$value, $key) {
            $value = $value.' '.$key;
        });

        //dd(implode(",", $finalPackages));
        return implode(",", $finalPackages);
    }

    public function getProductPackagingsWithPrice($microQuantity,array $productPackagings){
        /* $condition1 = array(
             74250 =>  'bora',//bora
             1350 =>  'catoon',//catoon
             30  =>  'packets',//packets
             1 =>  'pcs'//pcs
         );*/
       // dd($productPackagings);
        $finalPackages =[];

        $convertedMicroQty = intval($microQuantity);
        foreach( $productPackagings as $value => $packageName )
        {
            $remainingQuantity = $convertedMicroQty%$value;//remainder
            $packageQuantity = intval($convertedMicroQty/$value); //QUOTIENT
            if ($remainingQuantity == 0){

                array_push($finalPackages,[
                    'package_name' =>$packageName,
                    'package_quantity' =>$packageQuantity,
                    'micro_quantity' => $value
                ]);
                break;
            }
            if ($packageQuantity >= 1){
                //$finalPackages[$packageName] = $packageQuantity;
                array_push($finalPackages,[
                    'package_name' =>$packageName,
                    'package_quantity' =>$packageQuantity,
                    'micro_quantity' => $value
                ]);

                $convertedMicroQty = $remainingQuantity;
            }
        }
        return $finalPackages;
    }

    public function formatPackagingCombinationWithPackageCode($microQuantity,array $productPackagingsWithPackageCode){

        /* $condition1 = array(
            74250 =>  'PK0001',
            1350 =>  'PK0002',
            30  =>  'PK0005',
            1 =>  'PK0014'
        );*/
        // dd($microQuantity,$productPackagingsWithPackageCode);

        $finalPackages =[];

        $convertedMicroQty = intval($microQuantity);
        foreach( $productPackagingsWithPackageCode as $value => $packageCode )
        {
            $remainingQuantity = $convertedMicroQty%$value;//remainder
            $packageQuantity = intval($convertedMicroQty/$value); //QUOTIENT

           // dd($packageQuantity);
            if ($remainingQuantity == 0){
                $finalPackages[$packageCode] = [
                                                'micro_quantity' =>$value * $packageQuantity,
                                                'package_quantity' => $packageQuantity
                                               ];
                break;
            }
            if ($packageQuantity >= 1){
                $finalPackages[$packageCode] = [
                                                 'micro_quantity' => $value * $packageQuantity,
                                                 'package_quantity' => $packageQuantity,
                                               ];
                $convertedMicroQty = $remainingQuantity;
            }
        }

        //dd($finalPackages);

        return $finalPackages;
    }


}
