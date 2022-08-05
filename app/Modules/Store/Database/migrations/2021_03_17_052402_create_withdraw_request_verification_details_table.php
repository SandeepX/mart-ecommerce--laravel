<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawRequestVerificationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraw_request_verification_details', function (Blueprint $table) {
            $table->string('withdraw_request_verification_details_code');
            $table->string('store_balance_withdraw_request_code');
            $table->string('payment_method');
            $table->string('payment_body_code');
            $table->string('payment_verification_source');
            $table->double('amount',10,2);
            $table->string('payment_meta');
            $table->string('proof');
            $table->enum('status',['passed','failed'])->default('passed');
            $table->longText('remarks');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->primary(['withdraw_request_verification_details_code'],'pk_wrvd_wrvdc');
            $table->foreign('store_balance_withdraw_request_code','fk_sbwr_sbwrc')->references('store_balance_withdraw_request_code')->on('store_balance_withdraw_request');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table withdraw_request_verification_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdraw_request_verification_details');
    }
}
