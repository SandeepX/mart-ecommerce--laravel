<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/8/2020
 * Time: 10:45 AM
 */

namespace App\Modules\SystemSetting\Repositories\UrlSetting;


use App\Modules\SystemSetting\Models\EnvUrlSetting;

class EnvUrlSettingRepository
{

    public function getFirst(){

        return EnvUrlSetting::first();
    }

    public function save($validatedData){
        $validatedData['updated_by'] =getAuthUserCode();
        return EnvUrlSetting::create($validatedData)->fresh();
    }

    public function update(EnvUrlSetting $envUrlSetting, $validatedData){
        $validatedData['updated_by'] =getAuthUserCode();
        $envUrlSetting->update($validatedData);
        return $envUrlSetting->fresh();
    }
}