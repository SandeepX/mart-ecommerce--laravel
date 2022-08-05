<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnumInStoreMiscellaneousPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_miscellaneous_payments', function (Blueprint $table) {
            DB::statement("ALTER TABLE store_miscellaneous_payments MODIFY payment_for ENUM('initial_registration','load_balance','investment') NOT NULL");
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
            DB::statement("ALTER TABLE store_miscellaneous_payments MODIFY payment_for ENUM('initial_registration','load_balance') NOT NULL");
        });
    }
}
