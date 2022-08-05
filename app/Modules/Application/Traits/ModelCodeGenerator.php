<?php

namespace App\Modules\Application\Traits;

use Illuminate\Database\Eloquent\Model;

trait ModelCodeGenerator
{
    //model code with zero at start eg:0001,0002
    public function generateModelCode(
        Model $model,
        $modelCode,
        $modelPrefix,
        $initialIndex,
        $initialIndexLength
    ) {
        $modelPrefix = $modelPrefix;
        $initialIndex = $initialIndex;
        $model = self::withTrashed()->latest('id')->first();
        if ($model) {
            $codeTobePad = str_replace($modelPrefix, "", $model->{$modelCode}) + 1;
            $paddedCode = str_pad($codeTobePad, $initialIndexLength, '0', STR_PAD_LEFT);
            $latestModelCode = $modelPrefix . $paddedCode;
        } else {
            $latestModelCode = $modelPrefix . $initialIndex;
        }
        return $latestModelCode;
    }

    //model code without zero at start eg:1000,2000
    public function generateModelCodeWithOutZeroPadding(
        Model $model,
        $modelCode,
        $modelPrefix,
        $initialIndex,
        $withTrashed = false
    ) {
        $modelPrefix = $modelPrefix;
        $initialIndex = $initialIndex;

        if($withTrashed){
            $model = self::withTrashed()->latest('id')->first();
        }else{
            $model = self::latest('id')->first();
        }


        if ($model) {
            $newNumber = (int) (str_replace($modelPrefix, "", $model->{$modelCode}) + 1);
            $latestModelCode = $modelPrefix . $newNumber;
        } else {
            $latestModelCode = $modelPrefix . $initialIndex;
        }
        return $latestModelCode;
    }

    public function incrementPrimaryCodeWithOutZeroPadding($initialPrimaryCode,$modelPrefix){
        $newNumber = (int) (str_replace($modelPrefix, "", $initialPrimaryCode) + 1);
        $latestModelCode = $modelPrefix . $newNumber;

        return $latestModelCode;
    }
}
