<?php


namespace App\Modules\Store\Repositories\PreOrder;


use App\Modules\Store\Models\PreOrder\StorePreOrder;
use App\Modules\Store\Models\PreOrder\StorePreOrderStatusLog;

class StorePreOrderStatusLogRepository
{

    public function saveStatusLog(StorePreOrder $storePreOrder,$validatedData){

        $storePreOrderStatusLog = StorePreOrderStatusLog::updateOrCreate([
            'store_preorder_code' => $storePreOrder->store_preorder_code,
            'status' => $validatedData['status']
        ], [
            'remarks' => $validatedData['remarks']
        ]);

        return $storePreOrderStatusLog;
    }

    public function massSaveStatusLog(array $storePreOrdersCode,$validatedData){
        $toBeInsertedLogs =[];
        $authUserCode = getAuthUserCode();
        $statusLog = new StorePreOrderStatusLog();
        $latestPrimaryCode = $statusLog->generateCode();
        foreach ($storePreOrdersCode as $storePreOrderCode){
            array_push($toBeInsertedLogs,[
                'store_preorder_status_log_code'=> $latestPrimaryCode ,
                'store_preorder_code'=> $storePreOrderCode ,
                'status' => $validatedData['status'],
                'remarks' => $validatedData['remarks'],
                'updated_by' => $authUserCode
            ]);
            $latestPrimaryCode = $statusLog->incrementPrimaryCodeWithOutZeroPadding(
                $latestPrimaryCode,StorePreOrderStatusLog::MODEL_PREFIX);
        }

        StorePreOrderStatusLog::insert($toBeInsertedLogs);
    }
}
