<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletTransactionRemarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_transaction_remarks', function (Blueprint $table) {
            $table->string('wallet_transaction_remark_code');
            $table->string('wallet_transaction_code');
            $table->longText('remark');
            $table->string('created_by');
            $table->timestamps();

            $table->primary('wallet_transaction_remark_code','wtr_wtrc_p');
            $table->foreign('wallet_transaction_code')->references('wallet_transaction_code')
                                                              ->on('wallet_transaction');
            $table->foreign('created_by')->references('user_code')->on('users');
        });

        DB::statement('ALTER Table wallet_transaction_remarks add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_transaction_remarks');
    }
}
