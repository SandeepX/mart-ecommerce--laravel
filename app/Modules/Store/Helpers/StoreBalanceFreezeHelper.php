<?php


namespace App\Modules\Store\Helpers;


use App\Modules\Store\Models\Balance\StoreBalanceFreeze;
use Illuminate\Support\Facades\DB;

class StoreBalanceFreezeHelper
{
    public static function getFrozenBalance($storCode)
    {
        $frozenBalance=StoreBalanceFreeze::where('status',1)->where('store_code',$storCode)->get();
        $frozenAmount=$frozenBalance->sum('amount');

        return $frozenAmount;
    }
}
