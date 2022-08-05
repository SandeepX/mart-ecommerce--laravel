<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnAccountNoInStoreBalanceWithdrawRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_balance_withdraw_request', function (Blueprint $table) {
            $table->string('account_no')->after('status');
            $table->string('payment_body_code')->after('account_no');
            $table->longText('account_meta')->after('payment_body_code');
            $table->date('completion_estimation_date')->nullable()->after('account_meta');
            $table->string('created_by')->nullable()->after('completion_estimation_date');
            $table->enum('payment_method',['bank','remit','payment_gateway'])->default('bank')->after('created_by');
            $table->enum('priority',['high','low','medium'])->default('medium')->after('payment_method');

            $table->foreign('created_by')->references('user_code')->on('users');
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
            //
        });
    }
}
