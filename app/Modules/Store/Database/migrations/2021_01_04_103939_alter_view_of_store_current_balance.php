<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterViewOfStoreCurrentBalance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE OR REPLACE VIEW store_current_balance AS
           select DISTINCT mainBalance.store_code,loadedBalance,rewardBalance,interestBalance,withdrawnBalance,salesBalance, refundableCharges,royaltyCharges ,annualCharges, (IFNULL(loadedBalance,0)+IFNULL(rewardBalance,0)+ IFNULL(interestBalance,0)-IFNULL(withdrawnBalance,0)-IFNULL(salesBalance,0)-IFNULL(royaltyCharges,0)-IFNULL(refundableCharges,0)-IFNULL(annualCharges,0)) as balance from store_balance_master as mainBalance
    left join
      (select store_code,sum(transaction_amount) as loadedBalance from store_balance_master where transaction_type="load_balance" GROUP by store_code) as loadBalance on loadBalance.store_code= mainBalance.store_code
     left join
      (select store_code,sum(transaction_amount)  as withdrawnBalance from store_balance_master where transaction_type="withdraw" GROUP by store_code) as withDraw on withDraw.store_code=mainBalance.store_code
    left join
      (select store_code,sum(transaction_amount)  as salesBalance from store_balance_master where transaction_type="sales" GROUP by store_code) as sales on sales.store_code=mainBalance.store_code
      left join
      (select store_code,sum(transaction_amount)  as refundableCharges from store_balance_master where transaction_type="refundable" GROUP by store_code) as refundable on refundable.store_code=mainBalance.store_code
      left join
      (select store_code,sum(transaction_amount)  as royaltyCharges from store_balance_master where transaction_type="royalty" GROUP by store_code) as royalty on royalty.store_code=mainBalance.store_code
      left join
      (select store_code,sum(transaction_amount)  as interestBalance from store_balance_master where transaction_type="interest" GROUP by store_code) as interest on interest.store_code=mainBalance.store_code
      left join
      (select store_code,sum(transaction_amount)  as annualCharges from store_balance_master where transaction_type="annual_charge" GROUP by store_code) as annual_charge on annual_charge.store_code=mainBalance.store_code
      left join
      (select store_code,sum(transaction_amount)  as rewardBalance from store_balance_master where transaction_type="rewards" GROUP by store_code) as rewards on rewards.store_code=mainBalance.store_code
       ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('
          CREATE OR REPLACE VIEW store_current_balance AS
           select DISTINCT mainBalance.store_code ,loadedBalance ,withdrawnBalance,salesBalance ,(IFNULL(loadedBalance,0)-IFNULL(withdrawnBalance,0)-IFNULL(salesBalance,0)) as balance from store_balance_master as mainBalance
left join
  (select store_code,sum(transaction_amount) as loadedBalance from store_balance_master where transaction_type="load_balance" GROUP by store_code) as loadBalance on loadBalance.store_code= mainBalance.store_code
 left join
  (select store_code,sum(transaction_amount)  as withdrawnBalance from store_balance_master where transaction_type="withdraw" GROUP by store_code) as withDraw on withDraw.store_code=mainBalance.store_code
left join
  (select store_code,sum(transaction_amount)  as salesBalance from store_balance_master where transaction_type="sales" GROUP by store_code) as sales on sales.store_code=mainBalance.store_code


       ');
    }
}
