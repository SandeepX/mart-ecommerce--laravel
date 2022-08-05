<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/6/2020
 * Time: 2:12 PM
 */

namespace App\Modules\SystemSetting\Repositories\MailSetting;


use App\Modules\SystemSetting\Models\EnvMailSetting;

class EnvMailSettingRepository
{

    public function getFirst(){

        return EnvMailSetting::first();
    }

    public function save($validatedData){
        $validatedData['updated_by'] =getAuthUserCode();
        return EnvMailSetting::create($validatedData)->fresh();
    }

    public function update(EnvMailSetting $envMailSetting,$validatedData){
        $validatedData['updated_by'] =getAuthUserCode();
        $envMailSetting->update($validatedData);
        return $envMailSetting->fresh();
    }
}