<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/2/2020
 * Time: 10:29 AM
 */

namespace App\Modules\Store\Helpers;


use App\Modules\Store\Models\Payments\StoreOrderOfflinePayment;

class StoreOrderOfflinePaymentHelper
{

    public static function filterStoreOfflineOrderPaymentByParameters(array $filterParameters,$with=[]){

        $paymentStatus =in_array($filterParameters['payment_status'],StoreOrderOfflinePayment::PAYMENT_STATUSES);

        $offlinePayments = StoreOrderOfflinePayment::with($with)->when($filterParameters['store_code'],function ($query) use($filterParameters){
            $query-> where('store_code',$filterParameters['store_code']);
        })->when($paymentStatus,function ($query) use($filterParameters){
            $query-> where('payment_status',$filterParameters['payment_status']);
        })->latest()->get();

        return $offlinePayments;
    }

    public static function filterPaginatedStoreMiscPaymentByParameters(array $filterParameters,$paginateBy,$with=[]){

        $paymentStatus = (isset($filterParameters['payment_status']) && in_array($filterParameters['payment_status'],StoreOrderOfflinePayment::PAYMENT_STATUSES)) ? true :false;
        $paymentType = (isset($filterParameters['payment_type']) && in_array($filterParameters['payment_type'],StoreOrderOfflinePayment::PAYMENT_TYPE)) ? true :false;
        //$amountCondition=(isset($filterParameters['amount_condition']) && in_array($filterParameters['amount_condition'],['>','<', '>=','<=','='])) ? true :false;

        $offlinePayments = StoreOrderOfflinePayment::with($with)
            ->when(isset($filterParameters['store_code']),function ($query) use($filterParameters){
                $query-> where('store_code',$filterParameters['store_code']);
            })
            ->when(isset($filterParameters['store_order_code']),function ($query) use($filterParameters){
                $query-> where('store_order_code',$filterParameters['store_order_code']);
            })->when(isset($filterParameters['store_name']),function ($query) use($filterParameters){
                $query->whereHas('store', function ($query) use ($filterParameters) {
                    $query->where('store_name','like','%'.$filterParameters['store_name'] . '%');
                });
            })->when(isset($filterParameters['store_offline_payment_code']),function ($query) use($filterParameters){
                $query->where('store_offline_payment_code','like','%'.$filterParameters['store_offline_payment_code'] . '%');
            })->when($paymentStatus,function ($query) use($filterParameters){
                $query-> where('payment_status',$filterParameters['payment_status']);
            })->when($paymentType,function ($query) use($filterParameters){
                $query-> where('payment_type',$filterParameters['payment_type']);
            })->when(isset($filterParameters['payment_date_from']),function ($query) use($filterParameters){
                $query->whereDate('created_at','>=',date('y-m-d',strtotime($filterParameters['payment_date_from'])));
            })->when(isset($filterParameters['payment_date_to']),function ($query) use($filterParameters){
                $query->whereDate('created_at','<=',date('y-m-d',strtotime($filterParameters['payment_date_to'])));
            });

        $paginateBy = isset($filterParameters['records_per_page'])  ? $filterParameters['records_per_page'] : $paginateBy;

        $offlinePayments= $offlinePayments->latest()->paginate($paginateBy);
        return $offlinePayments;
    }
}