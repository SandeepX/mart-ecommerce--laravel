<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/4/2020
 * Time: 2:19 PM
 */

namespace App\Modules\SystemSetting\Services\IpAccessSetting;


use App\Modules\SystemSetting\Repositories\IpAccessSettingRepository;

use Exception;
use Illuminate\Support\Facades\DB;

class IpAccessSettingService
{
    private $ipAccessSettingRepository;

    public function __construct(IpAccessSettingRepository $ipAccessSettingRepository)
    {
        $this->ipAccessSettingRepository= $ipAccessSettingRepository;
    }

    public function getAllIpAccesses(){
        return $this->ipAccessSettingRepository->getAll();
    }


    public function findOrFailIpAccessSettingByCode($code){

        return $this->ipAccessSettingRepository->findOrFailByCode($code);
    }

    public function saveIpAccessSetting($validatedData){

        try{
            $validatedData['is_allowed'] = isset($validatedData['is_allowed']) ? 1 : 0;
            DB::beginTransaction();
            $ipAccessSetting=$this->ipAccessSettingRepository->save($validatedData);
            DB::commit();

            return $ipAccessSetting;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateIpAccessSetting($validatedData,$ipAccessCode){
        //dd($validatedData);
        try{
            $ipAccessSetting= $this->ipAccessSettingRepository->findOrFailByCode($ipAccessCode);
            $validatedData['is_allowed'] = isset($validatedData['is_allowed']) ? 1 : 0;
            DB::beginTransaction();
            $ipAccessSetting=$this->ipAccessSettingRepository->update($ipAccessSetting,$validatedData);
            DB::commit();

            return $ipAccessSetting;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

    public function deleteIpAccessSetting($ipAccessCode)
    {
        try {
            DB::beginTransaction();
            $ipAccessSetting = $this->ipAccessSettingRepository->findOrFailByCode($ipAccessCode);
            $ipAccessSetting = $this->ipAccessSettingRepository->delete($ipAccessSetting);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $ipAccessSetting;
    }
}