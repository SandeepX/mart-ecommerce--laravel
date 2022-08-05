<?php

namespace App\Modules\SystemSetting\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Modules\SystemSetting\Resources\GeneralSiteSettingResource;
use App\Modules\SystemSetting\Services\GeneralSetting\GeneralSettingService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GeneralSettingController extends Controller
{
    private $generalSettingService;
    
    public function __construct(GeneralSettingService $generalSettingService)
    {
        $this->generalSettingService = $generalSettingService;
    }

    public function getGeneralSiteSettings()
    {
        $generalSiteSetting = $this->generalSettingService->getGeneralSetting();
        if(!$generalSiteSetting){
            throw new ModelNotFoundException('No General Settings',404);
        }
        $generalSiteSetting = new GeneralSiteSettingResource($generalSiteSetting);
        return sendSuccessResponse('Data Found!', $generalSiteSetting);
    }


    public function getMaintenanceModeStatus()
    {
        $maintenanceMode = $this->generalSettingService->isMaintenanceModeOn();
        return sendSuccessResponse('Data Found !', ['maintenance_mode' => $maintenanceMode]);
    }


}