<?php

namespace App\Modules\AlpasalWarehouse\Repositories\StoreOrder;

use App\Modules\AlpasalWarehouse\Helpers\Store\StoreWarehouseHelper;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\Store\Models\StoreOrderDetails;
use App\Modules\Store\Models\StoreOrderDispatchDetail;
use App\Modules\Store\Models\StoreOrder;
use App\Modules\Store\Models\StoreOrderStatusLog;
use Carbon\Carbon;
use Exception;
use DB;
use Illuminate\Http\Request;

class WHStoreOrderRepository
{

    public function findOrFailStoreOrderByCodeForAuthWH($storeOrderCode,$with=[],$select='*')
    {
        $whStores = StoreWarehouseHelper::getStoresConnectedWithWarehouse(getAuthWarehouseCode());

        //dd($select);

        $storeOrderDetails =  StoreOrder::with($with)
                                         ->select($select)
                                      ->whereIn('store_code',$whStores)
                                     ->findorfail($storeOrderCode);

        return $storeOrderDetails;
    }


    public function updateStatus($status,$remarks,$storeOrder)
    {
        $storeOrder->delivery_status = $status;
        $storeOrder->save();

        //Insert Into store_order_status_log table
        StoreOrderStatusLog::updateOrCreate([
            'store_order_code' => $storeOrder->store_order_code,
            'status' => $status
        ],[
            'remarks'  => $remarks,
            'status_update_date' => Carbon::now(),
        ]);

        return $storeOrder->fresh();
    }

    public function saveStoreOrderDispatchDetails($storeOrderDispatchDetailData,$storeOrderCode){
       try{

           $storeOrderDispatchDetail = StoreOrderDispatchDetail::updateorCreate([
               'store_order_code'=>$storeOrderCode
           ],[
               'driver_name' => $storeOrderDispatchDetailData['driver_name'],
               'vehicle_type' =>$storeOrderDispatchDetailData['vehicle_type'],
               'vehicle_number' =>$storeOrderDispatchDetailData['vehicle_number'],
               'contact_number' =>$storeOrderDispatchDetailData['contact_number'],
               'expected_delivery_time' =>$storeOrderDispatchDetailData['expected_delivery_time'],
               'created_by'=>getAuthUserCode(),
           ]);
           return $storeOrderDispatchDetail;
       }catch(Exception $exception){
           throw $exception;
       }
    }

    public function findorFailStoreOrderDispatchDetail($storeOrderCode){
        $storeOrderDispatchDetail= StoreOrderDispatchDetail::where('store_order_code',$storeOrderCode)->first();

        return $storeOrderDispatchDetail;
    }


}
