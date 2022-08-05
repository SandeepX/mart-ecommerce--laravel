<?php

namespace App\Modules\SystemSetting\Repositories\SeoSetting;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\SystemSetting\Models\SeoSetting;

class SeoSettingRepository
{
    public function firstSeoSetting()
    {
        return SeoSetting::first();
    }

    public function storeSeoSetting($validatedSeoSetting)
    {
        $validatedSeoSetting['updated_by'] = getAuthUserCode();
        $seoSetting = SeoSetting::first();
        if($seoSetting){
            $seoSetting->update($validatedSeoSetting);
        }else{
            SeoSetting::create($validatedSeoSetting);
        }
        
    }

}