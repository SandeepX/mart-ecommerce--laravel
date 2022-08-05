<?php

namespace App\Modules\AlpasalWarehouse\Services\Setting;


use App\Modules\AlpasalWarehouse\Repositories\Setting\MinOrderSettingRepository;
use Illuminate\Support\Facades\DB;

use Exception;

class MinOrderSettingService
{
    private $minOrderSettingRepository;

    public function __construct(MinOrderSettingRepository $minOrderSettingRepository){
        $this->minOrderSettingRepository = $minOrderSettingRepository;
    }

    public function getAllMinOrderSettings($warehouseCode,$with=[],$select='*'){
        return $this->minOrderSettingRepository->getAllMinOrderSettings($warehouseCode,$with,$select);
    }

    public function  storeMinOrderSettings($validatedData){

        try{
            $validatedData['warehouse_code'] = getAuthWarehouseCode();
            $validatedData['created_by'] = getAuthUserCode();
            $validatedData['updated_by'] = getAuthUserCode();

            DB::beginTransaction();
            $minOrderSetting = $this->minOrderSettingRepository->storeMinOrderSettings($validatedData);
            DB::commit();
            return $minOrderSetting;

        }catch(Exception $exception){
            DB::rollBack();
            throw($exception);
        }

    }

    public function findOrFailBySettingMinOrderAmountCode($warehouseMinOrderAmountSettingCode){

        return  $minOrderSetting = $this->minOrderSettingRepository->findOrFailBySettingMinOrderAmountCode($warehouseMinOrderAmountSettingCode);

    }
    public function findActiveMinOrderSettingByWarehouseCode($warehouseCode){

        return  $minOrderSetting = $this->minOrderSettingRepository->findActiveMinOrderSettingByWarehouseCode($warehouseCode);

    }
    public function updateMinOrderSettings($validatedData,$warehouseMinOrderAmountSettingCode){

        try{
            $validatedData['updated_by'] = getAuthUserCode();

            $minOrderSetting = $this->minOrderSettingRepository->findOrFailBySettingMinOrderAmountCode($warehouseMinOrderAmountSettingCode);

            DB::beginTransaction();
            $minOrderSetting =  $this->minOrderSettingRepository->update($minOrderSetting,$validatedData);
            DB::commit();
            return $minOrderSetting;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public  function  deleteWarehouseMinOrderSetting($warehouseMinOrderAmountSettingCode){
        try {
            $minOrderSetting =$this->minOrderSettingRepository->findOrFailBySettingMinOrderAmountCode($warehouseMinOrderAmountSettingCode);

            DB::beginTransaction();
            $this->minOrderSettingRepository->delete($minOrderSetting);
            DB::commit();
            return $minOrderSetting;
        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }

    }

    public  function  changeMinOrderStatus($warehouseMinOrderAmountSettingCode){
        try {
            $minOrderSetting =$this->minOrderSettingRepository->findOrFailBySettingMinOrderAmountCode($warehouseMinOrderAmountSettingCode);

            DB::beginTransaction();
            $this->minOrderSettingRepository->changeMinOrderStatus($minOrderSetting);
            DB::commit();
            return $minOrderSetting;
        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }

    }
}
