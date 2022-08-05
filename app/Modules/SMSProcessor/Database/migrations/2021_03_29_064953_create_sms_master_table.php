<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_master', function (Blueprint $table) {

            $table->string('sms_master_code')->primary();
            //$table->primary(['sms_master_code'],'sp_primary');
            $table->text('request_body');
            $table->text('response_body');
            $table->enum('purpose',['load_balance','withdraw','testing','initial_registrations','preorder_refund','sales','sales_return','royalty','annual_charge','rewards','interest','refundable','sales_reconciliation_increment','sales_reconciliation_deduction','pre_orders_sales_reconciliation_increment','pre_orders_sales_reconciliation_deduction','refund_release','transaction_correction_increment','transaction_correction_deduction','janata_bank_increment','cash_received','preorder']);
            $table->string('purpose_code')->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER Table sms_master add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT First');
    }




    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_master');
    }
}
