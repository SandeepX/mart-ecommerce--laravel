<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_transaction', function (Blueprint $table) {
            $table->string('wallet_transaction_code')->unique()->primary();
            $table->string('reference_code')->unique()->comment('Customer Reference Code');
            $table->string('wallet_code');
            $table->string('wallet_transaction_purpose_code');
            $table->string('transaction_purpose_reference_code')->nullable();
            $table->double('amount');
            $table->string('transaction_uuid')->unique();
            $table->json('meta')->nullable();
            $table->text('remarks')->nullable();
            $table->string('proof_of_document')->nullable();
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('wallet_code')->references('wallet_code')->on('wallets');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
            $table->foreign('wallet_transaction_purpose_code')->references('wallet_transaction_purpose_code')->on('wallet_transaction_purpose');
        });
        DB::statement('ALTER Table wallet_transaction add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_transaction');
    }
}
