<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlinePaymentMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_payment_master', function (Blueprint $table) {
            $table->string('online_payment_master_code')->primary();
            $table->string('store_code');
            $table->string('wallet_code');
            $table->double('amount')->comment('in paisa');
            $table->enum('transaction_type',['load_balance']);
            $table->string('transaction_id')->unique();
            $table->json('request');
            $table->dateTime('request_at');
            $table->json('response')->nullable();
            $table->dateTime('response_at')->nullable();
            $table->enum('status',['pending','verified','rejected'])->default('pending');
            $table->string('created_by');
            $table->timestamps();

            $table->foreign('store_code')->references('store_code')->on('stores_detail');
            $table->foreign('wallet_code')->references('wallet_code')->on('digital_wallets');
            $table->foreign('created_by')->references('user_code')->on('users');
        });

        DB::statement('ALTER Table online_payment_master add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('online_payment_master');
    }
}
