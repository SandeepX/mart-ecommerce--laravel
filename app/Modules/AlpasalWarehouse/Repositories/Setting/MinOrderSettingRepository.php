<?php

namespace App\Modules\AlpasalWarehouse\Repositories\Setting;

use App\Modules\AlpasalWarehouse\Models\Setting\WarehouseMinOrderAmountSetting;

class MinOrderSettingRepository
{
    public function getAllMinOrderSettings($warehouseCode,$with=[],$select='*'){
        return WarehouseMinOrderAmountSetting::with($with)
            ->where('warehouse_code',$warehouseCode)
            ->select($select)->latest()->get();
    }

    public  function storeMinOrderSettings($validatedData){
        //dd($validatedData);
        return WarehouseMinOrderAmountSetting::create($validatedData);
    }

    public function findOrFailBySettingMinOrderAmountCode($warehouseMinOrderAmountSettingCode){

        $minOrderSetting = WarehouseMinOrderAmountSetting::where('warehouse_min_order_amount_setting_code',$warehouseMinOrderAmountSettingCode)
            ->first();

        if(!$minOrderSetting){
            throw new \Exception('No Such Minimum Order Amount Settings Found!');
        }
        return $minOrderSetting;
    }

    public function findActiveMinOrderSettingByWarehouseCode($warehouseCode){

        $minOrderSetting = WarehouseMinOrderAmountSetting::where('warehouse_code',$warehouseCode)
            ->where('status',1)
            ->first();

        return $minOrderSetting;
    }

    public function  update($minOrderSetting,$validatedData){
        $minOrderSetting =   $minOrderSetting->update($validatedData);
        return $minOrderSetting;
    }

    public function delete(WarehouseMinOrderAmountSetting  $minOrderAmountSetting){
        $minOrderAmountSetting->delete();
        return $minOrderAmountSetting;
    }

    public function changeMinOrderStatus($minOrderAmountSetting){
        if($minOrderAmountSetting->status == 1)
        {
            $minOrderAmountSetting->status = 0;
        }
        elseif($minOrderAmountSetting->status == 0)
        {
            $minOrderAmountSetting->status = 1;
        }
        $minOrderAmountSetting->save();
        return $minOrderAmountSetting;
    }

}

