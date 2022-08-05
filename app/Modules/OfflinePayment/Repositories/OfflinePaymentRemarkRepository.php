<?php

namespace App\Modules\OfflinePayment\Repositories;

use App\Modules\OfflinePayment\Models\OfflinePaymentRemark;

class OfflinePaymentRemarkRepository
{

    public function savePaymentRemarks($validatedData){
        $offlinePaymentRemark = OfflinePaymentRemark::create($validatedData);
        return $offlinePaymentRemark->fresh();
    }

}
