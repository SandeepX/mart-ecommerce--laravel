<?php

namespace App\Modules\SystemSetting\Services\GeneralSetting;

use App\Modules\SystemSetting\Repositories\GeneralSetting\GeneralSettingRepository;

use Illuminate\Support\Facades\DB;
use Exception;

class GeneralSettingService
{
    private $generalSettingRepository;
    public function __construct(GeneralSettingRepository $generalSettingRepository)
    {
        $this->generalSettingRepository = $generalSettingRepository;
    }


    public function getGeneralSetting()
    {
        return $this->generalSettingRepository->getGeneralSetting();
    }

    public function firstOrFailGeneralSetting()
    {
        return $this->generalSettingRepository->firstOrFailGeneralSetting();
    }


    public function isMaintenanceModeOn()
    {
        return $this->generalSettingRepository->isMaintenanceModeOn();
    }

    public function storeGeneralSetting($validatedGeneralSetting)
    {
        try{
            if(!isset($validatedGeneralSetting['is_maintenance_mode']))
                $validatedGeneralSetting['is_maintenance_mode'] = 0;

            if(!isset($validatedGeneralSetting['ip_filtering'])){
                $validatedGeneralSetting['ip_filtering'] = 0;
            }
            if(!isset($validatedGeneralSetting['sms_enable'])){
                $validatedGeneralSetting['sms_enable'] = 0;
            }

            DB::beginTransaction();
            $this->generalSettingRepository->storeGeneralSetting($validatedGeneralSetting);
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }
}
