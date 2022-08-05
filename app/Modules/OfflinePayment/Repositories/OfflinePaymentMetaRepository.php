<?php

namespace App\Modules\OfflinePayment\Repositories;

use App\Modules\OfflinePayment\Models\OfflinePaymentMaster;
use App\Modules\OfflinePayment\Models\OfflinePaymentMeta;
use Exception;

class OfflinePaymentMetaRepository
{
    public function findOrFailOfflinePaymentMetaByCode($paymentMetaCode){
        return OfflinePaymentMeta::where('offline_payment_meta_code',$paymentMetaCode)->firstOrFail();
    }

    public function getOfflinePaymentDetail($offlinePaymentCode)
    {
        $paymentDetailForLoadBalanceVerification = OfflinePaymentMeta::where('offline_payment_code',$offlinePaymentCode)
                                                                        ->get();
        return $paymentDetailForLoadBalanceVerification;
    }

    public function savePaymentMetaDetail(OfflinePaymentMaster $offlinePayment,$metaDetails)
    {
        try{
            $offlinePayment->paymentMetaData()->createMany($metaDetails);
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function createPaymentMetaDetails($validatedData)
    {
        $offlinePaymentMeta = OfflinePaymentMeta::create($validatedData)->fresh();
        return $offlinePaymentMeta;
    }

    public function updatePaymentMetaDetails(OfflinePaymentMeta $offlinePaymentMeta,$validatedData)
    {
        $offlinePaymentMeta = $offlinePaymentMeta->update($validatedData);
        return $offlinePaymentMeta;
    }

    public function getPaymentAdminDescriptionMetaDetail($offlinePaymentCode,$select)
    {
        $paymentDetailForLoadBalanceVerification = OfflinePaymentMeta::select($select)
            ->where('offline_payment_code',$offlinePaymentCode)
            ->where('key','admin_description')
            ->first();
        return $paymentDetailForLoadBalanceVerification;
    }

}
