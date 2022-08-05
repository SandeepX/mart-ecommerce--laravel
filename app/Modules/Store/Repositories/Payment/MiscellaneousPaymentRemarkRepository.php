<?php

namespace App\Modules\Store\Repositories\Payment;

use App\Modules\Store\Models\Payments\MiscellaneousPaymentRemark;

class MiscellaneousPaymentRemarkRepository
{
    public function savePaymentRemarks($validatedData){
         $storePaymentRemark = MiscellaneousPaymentRemark::create($validatedData);
         return $storePaymentRemark->fresh();
    }

}
