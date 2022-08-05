<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreTransactionCorrectionDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_transaction_correction_detail', function (Blueprint $table) {
            $table->string('store_transaction_correction_code');
            $table->string('store_balance_master_code');
            $table->string('transaction_code');
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->primary(['store_transaction_correction_code'],'pk_stcd_stcc');
            $table->foreign('store_balance_master_code','fk_stcd_sbmc')->references('store_balance_master_code')->on('store_balance_master');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table store_transaction_correction_detail add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_transaction_correction_detail');
    }
}
