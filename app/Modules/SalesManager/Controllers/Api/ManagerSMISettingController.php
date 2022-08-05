<?php


namespace App\Modules\SalesManager\Controllers\Api;

use App\Modules\SalesManager\Resources\MSMISettingResource;
use App\Modules\SalesManager\Services\ManagerSMISettingsService;
use Exception;


class ManagerSMISettingController
{
    private $managerSMISettingsService;

    public function __construct(ManagerSMISettingsService $managerSMISettingsService){
        $this->managerSMISettingsService = $managerSMISettingsService;
    }

    public function getLatestManagerSMISetting()
    {
        try {
            $managerSMISettings = $this->managerSMISettingsService->getLatestManagerSMISetting();
            $data = isset($managerSMISettings) ? new MSMISettingResource($managerSMISettings):[];
            return sendSuccessResponse('Data Found',$data);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }



}

