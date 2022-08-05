<?php


namespace App\Modules\Store\Repositories\StoreBalanceReconciliation;


use App\Modules\Store\Models\BalanceReconciliation\BalanceReconciliationUsageRemark;

class BalanceReconciliationUsageRemarkRepository
{

    public function storeBalanceReconciliationUsageRemarks($validatedData){
       return  BalanceReconciliationUsageRemark::create($validatedData);
    }

}
