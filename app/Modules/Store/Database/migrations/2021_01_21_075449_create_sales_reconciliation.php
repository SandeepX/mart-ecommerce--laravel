<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesReconciliation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_reconciliation', function (Blueprint $table) {
            $table->string('sales_reconciliation_code');
            $table->string('store_balance_master_code');
            $table->string('order_code')->nullable();
            $table->string('ref_bill_no')->nullable();
            $table->enum('type',['normal_store_order','store_pre_order']);
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->primary(['sales_reconciliation_code'],'pk_sr_src');
            $table->foreign('store_balance_master_code')->references('store_balance_master_code')->on('store_balance_master');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table sales_reconciliation add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_reconciliation');
    }
}
