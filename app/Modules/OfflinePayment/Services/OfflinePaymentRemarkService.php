<?php

namespace App\Modules\OfflinePayment\Services;

use App\Modules\OfflinePayment\Repositories\OfflinePaymentRemarkRepository;
use Exception;

class OfflinePaymentRemarkService
{
    private $offlinePaymentRemarkRepository;

    public function __construct(OfflinePaymentRemarkRepository $offlinePaymentRemarkRepository)
    {
        $this->offlinePaymentRemarkRepository = $offlinePaymentRemarkRepository;
    }

    public function saveRemarks($validatedData,$offlinePaymentCode){
        try{
            $validatedData['offline_payment_code'] = $offlinePaymentCode;
            $validatedData['created_by'] = getAuthUserCode();
            return $this->offlinePaymentRemarkRepository->savePaymentRemarks($validatedData);
        }catch (Exception $exception){
            throw $exception;
        }
    }
}
