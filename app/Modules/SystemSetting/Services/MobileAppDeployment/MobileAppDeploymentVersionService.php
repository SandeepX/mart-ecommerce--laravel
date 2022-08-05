<?php

namespace App\Modules\SystemSetting\Services\MobileAppDeployment;

use App\Modules\SystemSetting\Repositories\MobileAppDeploymentVersionRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class MobileAppDeploymentVersionService
{
    private $mobileAppDeploymentVersionRepository;
    public function __construct(
        MobileAppDeploymentVersionRepository $mobileAppDeploymentVersionRepository
    ){
        $this->mobileAppDeploymentVersionRepository = $mobileAppDeploymentVersionRepository;
    }

    public function getMobileAppDeploymentVersion(){
        return $this->mobileAppDeploymentVersionRepository->getMobileAppDeploymentVersion();
    }

    public function storeMobileAppDeploymentVersion($validatedData){
        try{
            DB::beginTransaction();
            $this->mobileAppDeploymentVersionRepository->storeMobileAppDeploymentVersion($validatedData);
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }


}
