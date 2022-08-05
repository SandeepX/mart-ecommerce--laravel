<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreOrderOfflinePaymentMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_order_offline_payment_meta', function (Blueprint $table) {

            $table->string('payment_meta_code')->unique()->primary();
            $table->string('store_order_offline_payment_code');
            $table->string('key');
            $table->string('value');

            $table->timestamps();

            $table->foreign('store_order_offline_payment_code','store_payment_code')->references('store_offline_payment_code')->on('store_order_offline_payments');
        });

        DB::statement('ALTER Table store_order_offline_payment_meta add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_order_offline_payment_meta');
    }
}
