<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnumInOnlinePaymentMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_payment_master', function (Blueprint $table) {
            DB::statement("ALTER TABLE online_payment_master MODIFY transaction_type ENUM('load_balance','investment') NOT NULL");
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
            DB::statement("ALTER TABLE online_payment_master MODIFY transaction_type ENUM('load_balance') NOT NULL");
        });
    }
}
