<?php

namespace App\Modules\Wallet\Interfaces;

use App\Modules\OfflinePayment\Models\OfflinePaymentMaster;
use Illuminate\Database\Eloquent\Model;

interface OfflineLoadBalanceInterface
{
    public function loadBalance(OfflinePaymentMaster $offlinePaymentMaster,$requestData=[]);

}
