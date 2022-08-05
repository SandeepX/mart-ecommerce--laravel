<?php

namespace App\Modules\Store\Classes;

use App\Modules\PaymentGateway\Models\OnlinePaymentMaster;
use App\Modules\Store\Event\StoreOnlineLoadBalanceResponseEvent;
use App\Modules\Wallet\Interfaces\OnlineLoadBalanceInterface;

class StoreOnlineLoadBalance implements OnlineLoadBalanceInterface
{
    public function loadBalance(OnlinePaymentMaster $onlinePaymentMaster)
    {
        event(new StoreOnlineLoadBalanceResponseEvent($onlinePaymentMaster));
    }
}
