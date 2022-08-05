<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreCurrentBalanceView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('
          CREATE VIEW store_current_balance AS
           select DISTINCT mainBalance.store_code ,loadedBalance ,withdrawnBalance,salesBalance ,(IFNULL(loadedBalance,0)-IFNULL(withdrawnBalance,0)-IFNULL(salesBalance,0)) as balance from store_balance_master as mainBalance
left join
  (select store_code,sum(transaction_amount) as loadedBalance from store_balance_master where transaction_type="load_balance" GROUP by store_code) as loadBalance on loadBalance.store_code= mainBalance.store_code
 left join
  (select store_code,sum(transaction_amount)  as withdrawnBalance from store_balance_master where transaction_type="withdraw" GROUP by store_code) as withDraw on withDraw.store_code=mainBalance.store_code
left join
  (select store_code,sum(transaction_amount)  as salesBalance from store_balance_master where transaction_type="sales" GROUP by store_code) as sales on sales.store_code=mainBalance.store_code


       ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_current_balance_view');
    }
}
