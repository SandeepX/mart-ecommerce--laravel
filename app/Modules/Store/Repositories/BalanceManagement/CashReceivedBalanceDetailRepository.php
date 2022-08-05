<?php


namespace App\Modules\Store\Repositories\BalanceManagement;


use App\Modules\Store\Models\CashReceivedBalanceDetail;
use DB;


class CashReceivedBalanceDetailRepository
{
    public function saveTransaction($validated)
    {

        return CashReceivedBalanceDetail::create($validated)->fresh();
    }
}
