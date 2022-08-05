<?php

namespace App\Modules\SystemSetting\Repositories\GeneralSetting;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\SystemSetting\Models\GeneralSetting;

use Exception;

class GeneralSettingRepository
{
    use ImageService;

    public function getGeneralSetting()
    {
        return GeneralSetting::first();
    }

    public function firstOrFailGeneralSetting()
    {
        return GeneralSetting::firstOrFail();
    }

    public function isMaintenanceModeOn()
    {
        $generalSetting = $this->firstOrFailGeneralSetting();
        return $generalSetting->isMaintenanceModeOn();
    }

    public function storeGeneralSetting($validatedGeneralSetting)
    {


        $images=[];
        try{
            $validatedGeneralSetting['updated_by'] = getAuthUserCode();
            //handle images
            if(isset($validatedGeneralSetting['logo'])){
                $validatedGeneralSetting['logo'] = $this->storeImageInServer($validatedGeneralSetting['logo'], 'uploads/general-setting');
                array_push($images,$validatedGeneralSetting['logo']);
            }

            if(isset($validatedGeneralSetting['admin_sidebar_logo'])){
                $validatedGeneralSetting['admin_sidebar_logo'] = $this->storeImageInServer($validatedGeneralSetting['admin_sidebar_logo'], 'uploads/general-setting');
                array_push($images,$validatedGeneralSetting['admin_sidebar_logo']);
            }
            if(isset($validatedGeneralSetting['favicon'])){
                $validatedGeneralSetting['favicon'] = $this->storeImageInServer($validatedGeneralSetting['favicon'], 'uploads/general-setting');
                array_push($images,$validatedGeneralSetting['favicon']);
            }
            $generalSetting = GeneralSetting::first();

            if($generalSetting){

                $generalSetting->update($validatedGeneralSetting);
            }else{
                GeneralSetting::create($validatedGeneralSetting);
            }
        }catch (Exception $exception){

            foreach ($images as $toBeDeletedImage){
                $this->deleteImageFromServer('uploads/general-setting/',$toBeDeletedImage);
            }
            throw $exception;
        }


    }


}
