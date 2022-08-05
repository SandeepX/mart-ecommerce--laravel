<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreBalanceSalesDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_balance_sales_details', function (Blueprint $table) {
            $table->string('store_bsd_code')->unique();
            $table->string('store_balance_master_code');
            $table->string('store_order_code');
            $table->timestamps();

            $table->foreign('store_balance_master_code')->references('store_balance_master_code')->on('store_balance_master');
            $table->foreign('store_order_code')->references('store_order_code')->on('store_orders');
        });
        DB::statement('ALTER Table store_balance_sales_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_balance_sales_details');
    }
}
