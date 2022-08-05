<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashRecivedBalanceControlDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_received_balance_details', function (Blueprint $table) {
            $table->string('store_crbd_code');
            $table->string('store_balance_master_code');
            $table->string('ref_bill_no');
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->primary(['store_crbd_code'],'pk_scrbd_scrbd');
            $table->foreign('store_balance_master_code','fk_scrbd_sbmc')->references('store_balance_master_code')->on('store_balance_master');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table cash_received_balance_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_received_balance_details');
    }
}
