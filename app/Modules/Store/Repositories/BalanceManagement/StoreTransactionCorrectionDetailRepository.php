<?php

namespace App\Modules\Store\Repositories\BalanceManagement;


use App\Modules\Store\Models\Payments\StoreTransactionCorrectionDetail;
use Carbon\Carbon;
use Exception;
use DB;


class StoreTransactionCorrectionDetailRepository
{
    public function  saveTransaction($validated){

        return StoreTransactionCorrectionDetail::create($validated)->fresh();
    }
}
