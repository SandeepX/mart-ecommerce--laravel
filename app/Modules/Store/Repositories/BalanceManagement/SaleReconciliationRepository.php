<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/22/2020
 * Time: 1:47 PM
 */

namespace App\Modules\Store\Repositories\BalanceManagement;


use App\Modules\Store\Models\Balance\StoreBalanceWithdrawRequest;
use App\Modules\Store\Models\Payments\SaleReconciliation;
use Carbon\Carbon;
use Exception;
use DB;


class SaleReconciliationRepository
{
   public function  saveTransaction($validated){
       //dd($validated);
       return SaleReconciliation::create($validated)->fresh();
   }
}
