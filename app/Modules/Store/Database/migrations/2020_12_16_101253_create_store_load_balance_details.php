<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreLoadBalanceDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_load_balance_details', function (Blueprint $table) {
            $table->string('store_load_balance_detail_code')->unique();
            $table->string('store_balance_master_code');
            $table->string('store_misc_payment_code');

            $table->primary(['store_load_balance_detail_code'],'slbdc_primary');
            $table->foreign('store_balance_master_code')->references('store_balance_master_code')->on('store_balance_master');
            $table->foreign('store_misc_payment_code')->references('store_misc_payment_code')->on('store_miscellaneous_payments');


            $table->timestamps();
        });
        DB::statement('ALTER Table store_load_balance_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */


    public function down()
    {
        Schema::dropIfExists('store_load_balance_details');
    }
}
