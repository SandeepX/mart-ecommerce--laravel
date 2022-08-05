<?php

namespace App\Modules\Store\Repositories;

use App\Modules\Store\Helpers\StoreWarehouseHelper;
use App\Modules\Store\Models\StoreOrder;
use App\Modules\Store\Models\StoreOrderDetails;
use App\Modules\Store\Models\StoreOrderStatusLog;
use Carbon\Carbon;

class StoreOrderRepository
{

    public function getAllStoreOrders(){
        return StoreOrder::latest()->get();
    }

    public function getAllStoreOrdersByStore($store){
        return $store->orders;
    }

    public function findStoreOrderByCode($storeCode,$with=[],$select='*')
    {
        return StoreOrder::with($with)->select($select)->findOrFail($storeCode);
    }


    public function findOrFailByCode($storeOrderCode,$with=[])
    {
        return StoreOrder::with($with)->findOrFail($storeOrderCode);
    }

    public function findOrFailByStoreCode($storeOrderCode,$storeCode,$with=[]){

        return StoreOrder::with($with)->where('store_order_code',$storeOrderCode)
            ->where('store_code',$storeCode)->firstOrFail();
    }

    public function filterStoreOrdersByStore($store){
        return $store->orders()->latest('id')->get();
    }

    public function filterStoreOrdersByStatus($status, $store){
        return $store->orders()->where('delivery_status', $status)->latest('id')->get();
    }



    private function oldCreateStoreOrder($validatedStoreOrder)
    {
        //Insert into store orders table
        $storeOrder = StoreOrder::create([
            'total_price' => array_sum($validatedStoreOrder['total_product_price']),
            'delivery_status' => 'pending'
        ]);

        //Insert Into Store Orders Details Table
        foreach($validatedStoreOrder['product_code'] as $key => $productCode){
            StoreOrderDetails::create([
                'store_order_code' => $storeOrder->store_order_code,
                'product_code' => $productCode,
                'product_variant_code' => $validatedStoreOrder['product_variant_code'][$key],
                'quantity' => $validatedStoreOrder['quantity'][$key],
                'initial_order_quantity'=>$validatedStoreOrder['quantity'][$key],
                'unit_rate' => $validatedStoreOrder['unit_rate'][$key],
            ]);
        }

        //Insert Into store_order_status_log table
        StoreOrderStatusLog::create([
            'store_order_code' => $storeOrder->store_order_code,
            'status' => $storeOrder->delivery_status,
            'status_update_date' => date('Y-m-d'),
        ]);

        return $storeOrder;
    }

    public function createStoreOrder($validatedStoreOrder)
    {
        //Insert into store orders table
        $storeOrder = StoreOrder::create([
           // 'total_price' => array_sum($validatedStoreOrder['total_product_price']),
            'total_price' => $validatedStoreOrder['total_product_price'],
            'delivery_status' => 'accepted',
            'payment_status'=> 1,
            'wh_code' => $validatedStoreOrder['warehouse_code']
        ]);

        //Insert Into Store Orders Details Table
        foreach($validatedStoreOrder['cartItems'] as  $cartItem){

            StoreOrderDetails::create([
                'store_order_code' => $storeOrder->store_order_code,
                'warehouse_code' =>$cartItem['warehouse_code'],
                'product_code' => $cartItem['product_code'],
                'product_variant_code' => $cartItem['product_variant'],
                'package_code' => $cartItem['package_code'],
                'product_packaging_history_code' => $cartItem['product_packaging_history_code'],
                'quantity' => $cartItem['quantity'],
                'initial_order_quantity'=> $cartItem['quantity'],
                'unit_rate' => $cartItem['unit_rate'],
                'is_taxable_product' => $cartItem['is_taxable_product'],
            ]);
        }

        //Insert Into store_order_status_log table
        StoreOrderStatusLog::create([
            'store_order_code' => $storeOrder->store_order_code,
            'status' => $storeOrder->delivery_status,
            'status_update_date' => Carbon::now(),
        ]);

        return $storeOrder;
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

    public function massUpdateDeliveryStatus(array $storeOrdersCode,$validatedData)
    {

        StoreOrder::whereIn('store_order_code',$storeOrdersCode)->update([
            'delivery_status' => $validatedData['status']
        ]);

        $toBeInsertedLogs =[];
        $authUserCode = getAuthUserCode();
        $statusLog = new StoreOrderStatusLog();
        $latestPrimaryCode = $statusLog->generateCode();
        foreach ($storeOrdersCode as $storeOrderCode){
            array_push($toBeInsertedLogs,[
               'store_order_status_log_code'=> $latestPrimaryCode ,
               'store_order_code'=> $storeOrderCode ,
                'status' => $validatedData['status'],
                'remarks' => $validatedData['remarks'],
                'status_update_date' => Carbon::now(),
                'updated_by' =>$authUserCode

            ]);

            $latestPrimaryCode = $statusLog->incrementPrimaryCodeWithOutZeroPadding(
                $latestPrimaryCode,StoreOrderStatusLog::MODEL_PREFIX);
        }
        //Insert Into store_order_status_log table
        StoreOrderStatusLog::insert($toBeInsertedLogs);
    }

    public function updatePaymentStatus(StoreOrder $storeOrder,$paymentStatus)
    {
        $storeOrder->payment_status = $paymentStatus;
        $storeOrder->save();
        return $storeOrder->fresh();
    }

    public function findOrderDetailByOrderProductAndVariantCode($storeOrderCode,$productCode,$variantCode){
        return StoreOrderDetails::where('store_order_code',$storeOrderCode)
            ->where('product_code',$productCode)
            ->where('product_variant_code',$variantCode)
            ->latest()
            ->firstorFail();
    }
    public function findOrderDetailByOrderProductAndVariantCodeWithPackageCode($storeOrderCode,$productCode,$variantCode,$packageCode){
        return StoreOrderDetails::where('store_order_code',$storeOrderCode)
            ->where('product_code',$productCode)
            ->where('product_variant_code',$variantCode)
            ->where('package_code',$packageCode)
            ->latest()
            ->firstorFail();
    }

    public function getStoreOrderDetailsByStoreOrderCode($storeOrderCode){
        return StoreOrderDetails::where('store_order_code',$storeOrderCode)
            ->latest()
            ->get();
    }

    public function updateStatusAndQuantityInBillMerge($storeOrderDetail,$validatedData){
        return $storeOrderDetail->update([
            'quantity'=>$validatedData['quantity'],
            'acceptance_status'=>$validatedData['status']
        ]);
    }

    public function updateHasMergedByStoreOrder($storeOrder){
        return $storeOrder->update(['has_merged'=>1,'delivery_status'=>'processing']);
    }

}
