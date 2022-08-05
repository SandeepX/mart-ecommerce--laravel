<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOnlinePaymentCodeToStoreMiscellaneousPaymentsTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_miscellaneous_payments', function (Blueprint $table) {
            //
            $table->string('online_payment_master_code')->nullable()->after('payment_type');

            $table->foreign('online_payment_master_code')->references('online_payment_master_code')
                ->on('online_payment_master');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_miscellaneous_payments', function (Blueprint $table) {
            //
        });
    }
}
