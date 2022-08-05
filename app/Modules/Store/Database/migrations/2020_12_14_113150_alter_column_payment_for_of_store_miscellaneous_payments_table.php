<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterColumnPaymentForOfStoreMiscellaneousPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_miscellaneous_payments', function (Blueprint $table) {
            DB::statement("ALTER TABLE store_miscellaneous_payments MODIFY payment_for ENUM('initial_registration', 'load_balance') NOT NULL");
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
            DB::statement("ALTER TABLE store_miscellaneous_payments MODIFY payment_for ENUM('initial_registration') NOT NULL");
        });
    }
}
