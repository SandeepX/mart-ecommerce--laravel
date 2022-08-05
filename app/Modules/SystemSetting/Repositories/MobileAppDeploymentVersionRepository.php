<?php

namespace App\Modules\SystemSetting\Repositories;

use App\Modules\SystemSetting\Models\MobileAppDeploymentVersion;

class MobileAppDeploymentVersionRepository
{

    public function getMobileAppDeploymentVersion(){
        return MobileAppDeploymentVersion::first();
    }

    public function storeMobileAppDeploymentVersion($validatedData){

        $mobileAppDeploymentVersion = MobileAppDeploymentVersion::first();
        if($mobileAppDeploymentVersion){
            $mobileAppDeploymentVersion->update($validatedData);
        }else{
            MobileAppDeploymentVersion::create($validatedData);
        }

    }

}
