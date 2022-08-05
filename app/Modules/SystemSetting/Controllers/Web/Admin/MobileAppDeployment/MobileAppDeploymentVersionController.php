<?php

namespace App\Modules\SystemSetting\Controllers\Web\Admin\MobileAppDeployment;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\SystemSetting\Requests\MobileAppDeploymentLogRequest;
use App\Modules\SystemSetting\Services\MobileAppDeployment\MobileAppDeploymentVersionService;
use Exception;

class MobileAppDeploymentVersionController extends BaseController
{
    public $title = 'Mobile App Deployment Version';
    public $base_route = 'admin.mobile-app-deployment-version';
    public $sub_icon = 'file';
    public $module = 'SystemSetting::';
    public $view = 'admin.mobile-app-deployment-version.';


    private $mobileAppDeploymentVersionService;
    public function __construct(MobileAppDeploymentVersionService $mobileAppDeploymentVersionService)
    {
        $this->mobileAppDeploymentVersionService = $mobileAppDeploymentVersionService;
    }

    public function edit(){
        $mobileAppDeploymentVersion = $this->mobileAppDeploymentVersionService->getMobileAppDeploymentVersion();
        return view(Parent::loadViewData($this->module.$this->view.'edit'),
            compact('mobileAppDeploymentVersion'));
    }

    public function store(MobileAppDeploymentLogRequest $request){
        try{
            $validatedData = $request->validated();
            $this->mobileAppDeploymentVersionService->storeMobileAppDeploymentVersion($validatedData);
            return redirect()->back()->with('success', 'General Setting Updated Successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function show(){
        $mobileAppDeploymentVersion = $this->mobileAppDeploymentVersionService->getMobileAppDeploymentVersion();
        return view(Parent::loadViewData($this->module.$this->view.'show'), compact('mobileAppDeploymentVersion'));
    }

}
