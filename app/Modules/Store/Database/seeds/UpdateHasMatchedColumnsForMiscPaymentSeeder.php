<?php
namespace App\Modules\Store\Database\seeds;

use Illuminate\Database\Seeder;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPayment;
use Illuminate\Support\Facades\DB;

class UpdateHasMatchedColumnsForMiscPaymentSeeder extends Seeder
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
            $storeMiscPayments = StoreMiscellaneousPayment::join('balance_reconciliation_usages',function ($join){
                                 $join->on('store_miscellaneous_payments.store_misc_payment_code','=','balance_reconciliation_usages.used_for_code')
                                 ->whereIn('used_for',['load_balance','initial_registration']);
                            })->where('has_matched',0)
                            ->update(['has_matched'=>1]);


             DB::commit();
             echo "Has Matched Sucessfully updated for Misc Payments";
        }catch (Exception $exception){
            DB::rollBack();
            echo $exception;
        }
    }
}
