<?php

namespace App\Modules\Store\Services\Payment;
use App\Modules\Store\Repositories\Payment\MiscellaneousPaymentRemarkRepository;
use Exception;

class MiscellaneousPaymentRemarksService
{
    private $miscellaneousPaymentRemarkRepository;
    public function __construct(MiscellaneousPaymentRemarkRepository $miscellaneousPaymentRemarkRepository)
    {
        $this->miscellaneousPaymentRemarkRepository = $miscellaneousPaymentRemarkRepository;
    }

    public function saveRemarks($validatedData,$storePaymentCode){
        try{
            $validatedData['store_misc_payment_code'] = $storePaymentCode;
            $validatedData['created_by'] = getAuthUserCode();
           return $this->miscellaneousPaymentRemarkRepository->savePaymentRemarks($validatedData);
        }catch (Exception $exception){
            throw $exception;
        }
    }

}
