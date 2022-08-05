<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/4/2020
 * Time: 3:46 PM
 */

namespace App\Modules\SystemSetting\Helpers;


use App\Modules\SystemSetting\Models\GeneralSetting;

class GeneralSettingHelper
{

    public static function isIpFilteringEnabled(){
        $generalSetting = GeneralSetting::first();
        if ($generalSetting && $generalSetting->isIpFilteringEnabled()){
            return true;
        }

        return false;
    }
}