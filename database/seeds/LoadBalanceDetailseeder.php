<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Modules\Store\Models\Payments\StoreLoadBalanceDetail;

class LoadBalanceDetailseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $query = 'SELECT
        smp.`store_misc_payment_code`,
        dt.store_balance_master_code,
        smp.store_code as smp_store_code,
        dt.store_code as dt_store_code,
        smp.amount as smp_amount,
        dt.transaction_amount as st_txamount,
        smp.updated_at as smp_updated_at_verified,
        dt.created_at as sbm_transaction_date
        from  store_miscellaneous_payments smp
        join (select store_code,`store_balance_master_code`,`transaction_amount`,`created_at`,`updated_at` from store_balance_master sbm
                    where
                   sbm.store_balance_master_code NOT in
                   (select store_balance_master_code from store_load_balance_details)
                   and
                    sbm.transaction_type = "load_balance") dt
                     on dt.store_code = smp.store_code
         and dt.transaction_amount = smp.amount
         and dt.created_at = smp.updated_at
         and smp.verification_status = "verified"';

        $results = DB::select($query);

        //dd($results);
        try{
            DB::beginTransaction();
            foreach ($results as $result){
                $data= [];
                $data['store_balance_master_code']  = $result->store_balance_master_code;
                $data['store_misc_payment_code']  = $result->store_misc_payment_code;
               StoreLoadBalanceDetail::create($data);
               echo "completed for balance code: ".$data['store_balance_master_code']." misc payment code: ".$data['store_misc_payment_code']."\n";
            }

           DB::commit();
            echo "completed sucessfully sucessfully.";
        }catch (Exception $exception){
            DB::rollBack();
            echo $exception->getMessage();
        }

    }
}
