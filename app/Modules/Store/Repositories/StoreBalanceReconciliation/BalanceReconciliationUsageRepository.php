<?php
/**
 * Created by PhpStorm.
 * User: sandeep pant
 * Date: 1/14/2021
 * Time: 1:47 PM
 */

namespace App\Modules\Store\Repositories\StoreBalanceReconciliation;
use App\Modules\Store\Models\BalanceReconciliation\BalanceReconciliationUsage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BalanceReconciliationUsageRepository
{
    public function findOrFailByBalanceReconciliationUsageCode($balanceReconciliationUsageCode){
        $balanceReconciliationUsage = BalanceReconciliationUsage::where('balance_reconciliation_usages_code',$balanceReconciliationUsageCode)
                                     ->first();

        if(!$balanceReconciliationUsage){
            throw new ModelNotFoundException('No Such balance reconciliation usage Found');
        }
        return $balanceReconciliationUsage;

    }
   public function storeBalanceReconiliationUsage($balanceReconciliationUsageData)
   {
       return BalanceReconciliationUsage::create($balanceReconciliationUsageData);
   }
   public function getBalanceReconciliationUsage($usedForCode){
       return BalanceReconciliationUsage::where('used_for_code',$usedForCode)
                                        ->first();
   }

   public function update(BalanceReconciliationUsage $balanceReconciliationUsage,$validatedData){
       $balanceReconciliationUsage->update($validatedData);
       return $balanceReconciliationUsage->refresh();
   }
}












