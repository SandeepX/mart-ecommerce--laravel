<?php

namespace App\Modules\PaymentGateway\Repositories;

use App\Modules\PaymentGateway\Models\OnlinePaymentMaster;
use Exception;

class OnlinePaymentMetaRepository
{
    public function savePaymentMetaDetail(OnlinePaymentMaster $onlinePaymentMaster,$metaDetails)
    {
        try{
            $onlinePaymentMaster->paymentMetaData()->createMany($metaDetails);
        }catch (Exception $exception){
            throw $exception;
        }
    }

}
