<?php

namespace App\Modules\SystemSetting\Services\SeoSetting;

use App\Modules\SystemSetting\Repositories\SeoSetting\SeoSettingRepository;

class SeoSettingService
{
    private $SeoSettingRepository;
    public function __construct(SeoSettingRepository $seoSettingRepository)
    {
        $this->seoSettingRepository = $seoSettingRepository;
    }

    public function getSeoSetting()
    {
        return $this->seoSettingRepository->firstSeoSetting();
    }

    public function storeSeoSetting($validatedSeoSetting)
    {   
        // $validatedSeoSetting['keywords'] = explode(',', $validatedSeoSetting['keywords']);
        $validatedSeoSetting['keywords'] = explode(',', $validatedSeoSetting['keywords']);
        $validatedSeoSetting['keywords'] = json_encode($validatedSeoSetting['keywords']);        
        return $this->seoSettingRepository->storeSeoSetting($validatedSeoSetting);
    }
}