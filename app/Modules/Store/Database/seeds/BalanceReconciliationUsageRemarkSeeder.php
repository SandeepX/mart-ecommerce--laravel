<?php

namespace  App\Modules\Store\Database\seeds;

use App\Modules\Store\Models\BalanceReconciliation\BalanceReconciliationUsage;
use App\Modules\Store\Models\BalanceReconciliation\BalanceReconciliationUsageRemark;
use Illuminate\Database\Seeder;
use Exception;
use Illuminate\Support\Facades\DB;

class BalanceReconciliationUsageRemarkSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            DB::beginTransaction();
           $balanceReconciliationUsages =  BalanceReconciliationUsage::whereNotNull('remarks')->get();

           foreach ($balanceReconciliationUsages as $balanceReconciliationUsage){
               $data = [];
               $data['balance_reconciliation_usages_code'] = $balanceReconciliationUsage->balance_reconciliation_usages_code;
               $data['remark'] = $balanceReconciliationUsage->remarks;
               $data['created_by'] = 'U00000001';
               $data['updated_by'] = 'U00000001';
               BalanceReconciliationUsageRemark::create($data);

               echo "Balance reconciliation remarks added for ".$balanceReconciliationUsage->balance_reconciliation_usages_code." \n";
           }
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
        }
    }



}
