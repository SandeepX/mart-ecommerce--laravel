<?php


namespace App\Modules\OfflinePayment\Helpers;

use App\Modules\OfflinePayment\Models\OfflinePaymentMaster;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPayment;

class OfflinePaymentsFilterHelper
{
    public static function filterAllOfflinePaymentsByPaymentType(array $filterParameters,$paginationBy,$with=[])
    {
        $paymentType = (isset($filterParameters['payment_type']) && in_array($filterParameters['payment_type'],StoreMiscellaneousPayment::PAYMENT_TYPE)) ? true :false;
        $amountCondition = (isset($filterParameters['amount_condition']) && in_array($filterParameters['amount_condition'], ['>', '<', '>=', '<=', '='])) ? true : false;
        $paymentStatus = (isset($filterParameters['payment_status']) && in_array($filterParameters['payment_status'], StoreMiscellaneousPayment::VERIFICATION_STATUSES)) ? true : false;


        $offflinePayments=  StoreMiscellaneousPayment::with($with)
            ->when(isset($filterParameters['payment_for']),function ($query) use ($filterParameters){
                $query->where('payment_for',$filterParameters['payment_for']);
            })
            ->when(isset($filterParameters['user_type']),function ($query) use ($filterParameters){
                $query->whereHas('submittedBy', function ($query) use ($filterParameters) {
                    $query->whereHas('userType', function ($query) use ($filterParameters) {
                        $query->where('user_type_name', 'like', '%' . $filterParameters['user_type'] . '%');
                    });
                });
            })
            ->when(isset($filterParameters['payment_code']),function ($query) use ($filterParameters){
                $query->where('store_misc_payment_code',$filterParameters['payment_code']);
            })
            ->when($paymentType,function ($query) use ($filterParameters){
                $query->where('payment_type',$filterParameters['payment_type']);
            })
            ->when($amountCondition && isset($filterParameters['amount']), function ($query) use ($filterParameters) {
                $query->where('amount', $filterParameters['amount_condition'], $filterParameters['amount']);
            })
            ->when(isset($filterParameters['payment_date_from']), function ($query) use ($filterParameters) {
                $query->whereDate('created_at', '>=', date('y-m-d', strtotime($filterParameters['payment_date_from'])));
            })
            ->when(isset($filterParameters['payment_date_to']), function ($query) use ($filterParameters) {
                $query->whereDate('created_at', '<=', date('y-m-d', strtotime($filterParameters['payment_date_to'])));
            })
            ->when($paymentStatus,function ($query) use ($filterParameters){
                $query->where('verification_status',$filterParameters['payment_status']);
            })
            ->when(isset($filterParameters['has_matched']),function ($query) use ($filterParameters){
                $query->where('has_matched',$filterParameters['has_matched']);
            })
            ->latest()
            ->paginate($paginationBy);

        return $offflinePayments;

    }

    public static function getAllOfflinePaymentsByFilterParameters(array $filterParameters,$paginateBy,$with=[]){

        $paymentType = (isset($filterParameters['payment_type']) && in_array($filterParameters['payment_type'],OfflinePaymentMaster::PAYMENT_TYPE)) ? true :false;
        $amountCondition = (isset($filterParameters['amount_condition']) && in_array($filterParameters['amount_condition'], ['>', '<', '>=', '<=', '='])) ? true : false;
        $paymentStatus = (isset($filterParameters['payment_status']) && in_array($filterParameters['payment_status'], OfflinePaymentMaster::VERIFICATION_STATUSES)) ? true : false;


        $offflinePayments=  OfflinePaymentMaster::with($with)
            ->when(isset($filterParameters['payment_for']),function ($query) use ($filterParameters){
                $query->where('payment_for',$filterParameters['payment_for']);
            })
            ->when(isset($filterParameters['user_type']),function ($query) use ($filterParameters){
                $query->whereHas('submittedBy', function ($query) use ($filterParameters) {
                    $query->whereHas('userType', function ($query) use ($filterParameters) {
                        $query->where('user_type_name', 'like', '%' . $filterParameters['user_type'] . '%');
                    });
                });
            })
            ->when(isset($filterParameters['offline_payment_holder_code']),function($query) use ($filterParameters){
                $query->where('offline_payment_holder_code',$filterParameters['offline_payment_holder_code']);
            })
            ->when(isset($filterParameters['payment_code']),function ($query) use ($filterParameters){
                $query->where('offline_payment_code',$filterParameters['payment_code']);
            })
            ->when(isset($filterParameters['payment_holder_type']),function ($query) use ($filterParameters){
                $query->where('payment_holder_type',$filterParameters['payment_holder_type']);
            })
            ->when($paymentType,function ($query) use ($filterParameters){
                $query->where('payment_type',$filterParameters['payment_type']);
            })
            ->when($amountCondition && isset($filterParameters['amount']), function ($query) use ($filterParameters) {
                $query->where('amount', $filterParameters['amount_condition'], $filterParameters['amount']);
            })
            ->when(isset($filterParameters['payment_date_from']), function ($query) use ($filterParameters) {
                $query->whereDate('created_at', '>=', date('y-m-d', strtotime($filterParameters['payment_date_from'])));
            })
            ->when(isset($filterParameters['payment_date_to']), function ($query) use ($filterParameters) {
                $query->whereDate('created_at', '<=', date('y-m-d', strtotime($filterParameters['payment_date_to'])));
            })
            ->when($paymentStatus,function ($query) use ($filterParameters){
                $query->where('verification_status',$filterParameters['payment_status']);
            })
            ->when(isset($filterParameters['has_matched']),function ($query) use ($filterParameters){
                $query->where('has_matched',$filterParameters['has_matched']);
            })
            ->latest()
            ->paginate($paginateBy);

        return $offflinePayments;

    }



}
