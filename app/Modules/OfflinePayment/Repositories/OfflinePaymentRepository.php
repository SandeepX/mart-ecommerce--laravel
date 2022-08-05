<?php

namespace App\Modules\OfflinePayment\Repositories;

use App\Modules\OfflinePayment\Models\OfflinePaymentMaster;
use Carbon\Carbon;

class OfflinePaymentRepository
{
    public function findOrFailByCode($offlinePaymentCode,$with=[]){
        return OfflinePaymentMaster::with($with)->where('offline_payment_code',$offlinePaymentCode)
                                     ->firstOrFail();
    }

    public function findOrFailByPaymentType($offlinePaymentCode,$paymentFor,$with=[]){
        return OfflinePaymentMaster::with($with)->where('offline_payment_code',$offlinePaymentCode)
            ->where('payment_for',$paymentFor)
            ->firstOrFail();
    }

    public function save($validatedData){
        return OfflinePaymentMaster::create($validatedData)->fresh();
    }

    public function getLatestOfflinePaymentVerificationStatus($offlineData){

        $offlinePaymentMaster = OfflinePaymentMaster::where('offline_payment_holder_code',$offlineData['offline_payment_holder_code'])
                                                      ->where('payment_for',$offlineData['payment_for'] )
                                                      ->where('verification_status',$offlineData['verification_status'])
                                                      ->where('payment_type',$offlineData['payment_type'])
                                                      ->orderBy('created_at','DESC')
                                                      ->first();
        return $offlinePaymentMaster;
    }

    public function updateOfflinePayment(OfflinePaymentMaster $offlinePayment,$validatedData){
        $offlinePayment = $offlinePayment->update($validatedData);
        return $offlinePayment;
    }

    public function updateVerificationStatus(OfflinePaymentMaster $offlinePayment,$validatedData){
        $offlinePayment->verification_status = $validatedData['verification_status'];
        $offlinePayment->remarks = $validatedData['remarks'];
        $offlinePayment->responded_by = getAuthUserCode();
        $offlinePayment->responded_at = Carbon::now();
        $offlinePayment->questions_checked_meta = isset($validatedData['questions_checked_meta']) ? $validatedData['questions_checked_meta'] : NULL;
        $offlinePayment->save();
        return $offlinePayment;
    }

    public function getBalanceOfflinePaymentForMatching($offlinePaymentData){


        $description = explode(' ',strip_tags($offlinePaymentData['description']));

        $offlinePaymentDetail = OfflinePaymentMaster::where(function ($query) use ($offlinePaymentData){

            if($offlinePaymentData['payment_method']=='bank')
            {
                $query->whereHas('paymentMetaData',function ($query) use ($offlinePaymentData){
                    $query->where('key','bank_code')->where('value',$offlinePaymentData['payment_body_code']);
                });
            }
            if($offlinePaymentData['payment_method'] =='remit')
            {
                $query->whereHas('paymentMetaData',function ($query) use ($offlinePaymentData){
                    $query->where('key','remit_code')->where('value',$offlinePaymentData['payment_body_code']);
                });
            }

            if($offlinePaymentData['payment_method'] =='digital_wallet')
            {
                $query->whereHas('paymentMetaData',function ($query) use ($offlinePaymentData){
                    $query->where('key','wallet_code')->where('value',$offlinePaymentData['payment_body_code']);
                });
            }
        })
            ->where('transaction_date',$offlinePaymentData['transaction_date'])
            ->where('amount',$offlinePaymentData['transaction_amount'])
            ->where('payment_for','load_balance')
            ->where('verification_status','pending')
            ->where('has_matched',0)
            ->where(function ($query) use ($offlinePaymentData,$description){
                $query->whereIn('deposited_by',$description);
                $query->orWhereIn('contact_phone_no',$description);
                $query->orWhereHas('paymentMetaData',function ($query) use ($offlinePaymentData,$description){
                    $query->where('key','remark')->whereIn('value',$description);
                });
                $query->orWhereHas('paymentMetaData',function ($query) use ($offlinePaymentData,$description){
                    $query->where('key','cheque_no')->where('value',$description);
                });
                $query->orWhereHas('paymentMetaData',function ($query) use ($offlinePaymentData,$description){

                    $query->where('key','transaction_number')->where('value',$description);
                });
            })
            ->orderBy('created_at','ASC')
            ->first();
        return $offlinePaymentDetail;
    }

    public function updateHasMatched($offlinePayment)
    {
        return $offlinePayment->update([
            'has_matched' => 1,
        ]);
    }

}
