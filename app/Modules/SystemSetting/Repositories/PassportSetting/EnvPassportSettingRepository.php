<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/7/2020
 * Time: 3:37 PM
 */

namespace App\Modules\SystemSetting\Repositories\PassportSetting;


use App\Modules\SystemSetting\Models\EnvPassportSetting;

class EnvPassportSettingRepository
{

    public function getFirst(){

        return EnvPassportSetting::first();
    }

    public function save($validatedData){
        $validatedData['updated_by'] =getAuthUserCode();
        return EnvPassportSetting::create($validatedData)->fresh();
    }

    public function update(EnvPassportSetting $envPassportSetting,$validatedData){
        $validatedData['updated_by'] =getAuthUserCode();
        $envPassportSetting->update($validatedData);
        return $envPassportSetting->fresh();
    }
}