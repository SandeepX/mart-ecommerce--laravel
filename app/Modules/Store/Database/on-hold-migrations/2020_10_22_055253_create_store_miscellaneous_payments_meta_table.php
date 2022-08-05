<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreMiscellaneousPaymentsMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_miscellaneous_payments_meta', function (Blueprint $table) {

            $table->string('payment_meta_code')->unique()->primary();
            $table->string('store_misc_payment_code');
            $table->string('key');
            $table->string('value');

            $table->timestamps();

            $table->foreign('store_misc_payment_code','meta_payment_foreign')->references('store_misc_payment_code')->on('store_miscellaneous_payments');
        });

        DB::statement('ALTER Table store_miscellaneous_payments_meta add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_miscellaneous_payments_meta');
    }
}
