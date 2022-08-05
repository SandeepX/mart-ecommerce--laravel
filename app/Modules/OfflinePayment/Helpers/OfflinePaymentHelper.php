<?php

namespace App\Modules\OfflinePayment\Helpers;

use App\Modules\OfflinePayment\Models\OfflinePaymentMaster;

class OfflinePaymentHelper
{

    public static function getOfflinePaymentHolderName(OfflinePaymentMaster $offlinePayment)
    {
        if($offlinePayment->payment_holder_type == 'store'){
            $holderName = $offlinePayment->offlinePaymentable->store_name;
        }
        if($offlinePayment->payment_holder_type == 'vendor'){
            $holderName = $offlinePayment->offlinePaymentable->vendor_name;
        }
        if($offlinePayment->payment_holder_type == 'manager'){
            $holderName = $offlinePayment->offlinePaymentable->manager_name;
        }
        return $holderName;
    }

}
