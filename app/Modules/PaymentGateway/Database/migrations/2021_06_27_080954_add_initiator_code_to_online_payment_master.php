<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInitiatorCodeToOnlinePaymentMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_payment_master', function (Blueprint $table) {
            $table->string('payment_initiator')->nullable();
            $table->renameColumn('store_code','initiator_code')->nullable()->change();
            $table->string('reference_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('online_payment_master', function (Blueprint $table) {
            //
        });
    }
}
