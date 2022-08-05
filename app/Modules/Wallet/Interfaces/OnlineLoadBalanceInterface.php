<?php

namespace App\Modules\Wallet\Interfaces;

use App\Modules\PaymentGateway\Models\OnlinePaymentMaster;

interface OnlineLoadBalanceInterface
{
    public function loadBalance(OnlinePaymentMaster $onlinePaymentMaster);
}
