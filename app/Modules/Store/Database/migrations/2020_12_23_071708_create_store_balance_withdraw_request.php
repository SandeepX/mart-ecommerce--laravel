<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreBalanceWithdrawRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_balance_withdraw_request', function (Blueprint $table) {

            $table->string('store_balance_withdraw_request_code')->unique('uq_sbwr');

            $table->primary(['store_balance_withdraw_request_code'],'sbwr_primary');

            $table->string('store_code');
            $table->longtext('reason')->nullable();
            $table->string('remarks')->nullable();

            $table->decimal('requested_amount',10,2);
            $table->string('document')->nullable();
            $table->datetime('withdraw_date')->nullable();
            $table->enum('status',['pending','completed','rejected','processing'])->default('pending');
            $table->string('verified_by')->nullable();
            $table->datetime('verified_at')->nullable();

            $table->foreign('store_code')->references('store_code')->on('stores_detail');
            $table->foreign('verified_by')->references('user_code')->on('users');

            $table->timestamps();
        });

        DB::statement('ALTER Table store_balance_withdraw_request add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_balance_withdraw_request');
    }
}
