<?php


namespace App\Modules\SalesManager\Services;


use App\Modules\SalesManager\Repositories\ManagerSMISettingRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ManagerSMISettingsService
{

    private $msmiSettingRepo;

    public function __construct(ManagerSMISettingRepository $msmiSettingRepo)
    {
        $this->msmiSettingRepo = $msmiSettingRepo;
    }

    public function getAllManagerSMISetting()
    {
        try{
            $msmiSetting = $this->msmiSettingRepo->getAllManagerSMISettings();
            if(!$msmiSetting){
                throw new Exception('Data not Found',404);
            }
            return $msmiSetting;
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function getLatestManagerSMISetting()
    {
        return $this->msmiSettingRepo->findLatestManagerSMISetting();
    }

    public function getSMISettingByCode($MSMICode)
    {
        return $this->msmiSettingRepo->findOrFailSMISettingByCode($MSMICode);
    }

    public function storeSMISetting($validatedData)
    {
        DB::beginTransaction();
        try {
            $managerSMISetting = $this->msmiSettingRepo->store($validatedData);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $managerSMISetting;

    }

    public function deleteMSMISetting($MSMICode)
    {
        DB::beginTransaction();
        try{
            $managerSMISettingDetail = $this->getSMISettingByCode($MSMICode);
            $this->msmiSettingRepo->delete($managerSMISettingDetail);

            DB::commit();
            return true;

        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateManagerSMISetting($validatedData, $managerSMISettingCode)
    {
        DB::beginTransaction();
        try {
            $managerSMISettingDetail = $this->getSMISettingByCode($managerSMISettingCode);
            $this->msmiSettingRepo->update($validatedData, $managerSMISettingDetail);

            DB::commit();
            return true;

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }

    }




}
