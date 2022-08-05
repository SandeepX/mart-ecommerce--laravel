<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnumInStoreBalanceWithdrawRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_balance_withdraw_request', function (Blueprint $table) {
            DB::statement("ALTER TABLE store_balance_withdraw_request MODIFY status ENUM('pending','completed','rejected','processing','cancelled') NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_balance_withdraw_request', function (Blueprint $table) {
            DB::statement("ALTER TABLE store_balance_withdraw_request MODIFY status ENUM('pending','completed','rejected','processing') NOT NULL");
        });
    }
}
