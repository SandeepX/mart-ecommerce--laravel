<?php

namespace App\Modules\SystemSetting\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Modules\SystemSetting\Services\MobileAppDeployment\MobileAppDeploymentVersionService;
use Exception;

class MobileAppDeploymentVersionApiController extends Controller
{
    private $mobileAppDeploymentVersionService;
    public function __construct(MobileAppDeploymentVersionService $mobileAppDeploymentVersionService)
    {
        $this->mobileAppDeploymentVersionService = $mobileAppDeploymentVersionService;
    }

    public function getMobileAppDeploymentVersion(){
        try{
            $mobileAppDevelopmentVersion = $this->mobileAppDeploymentVersionService->getMobileAppDeploymentVersion();
            return  sendSuccessResponse('Mobile App Deployment Latest Versions', [
               'manager' =>[
                   'version' => isset($mobileAppDevelopmentVersion) ? $mobileAppDevelopmentVersion->manager_version : NULL,
                   'build_number' => isset($mobileAppDevelopmentVersion) ? $mobileAppDevelopmentVersion->manager_build_number : NULL,
                ],
                'store' => [
                    'version' => isset($mobileAppDevelopmentVersion) ? $mobileAppDevelopmentVersion->store_version : NULL,
                    'build_number' => isset($mobileAppDevelopmentVersion) ? $mobileAppDevelopmentVersion->store_build_number : NULL,
                ]
            ]);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

}
