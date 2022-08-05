<?php

namespace App\Modules\Store\Classes;

use App\Modules\OfflinePayment\Models\OfflinePaymentMaster;
use App\Modules\Store\Events\LoadBalanceCompletedEvent;
use App\Modules\Store\Models\Store;
use App\Modules\Wallet\Interfaces\OfflineLoadBalanceInterface;

class StoreOfflineLoadBalance implements OfflineLoadBalanceInterface
{

    public function loadBalance(OfflinePaymentMaster $offlinePaymentMaster,$validatedData=[])
    {
        event(new LoadBalanceCompletedEvent($offlinePaymentMaster, $validatedData));
    }

}
