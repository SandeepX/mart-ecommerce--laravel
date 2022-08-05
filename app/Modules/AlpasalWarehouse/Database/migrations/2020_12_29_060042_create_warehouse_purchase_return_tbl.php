<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousePurchaseReturnTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_purchase_return', function (Blueprint $table) {

            $table->string('warehouse_purchase_return_code')->unique('wpr_wprc_uq');
            $table->string('warehouse_order_code');
            $table->string('vendor_code');
            $table->string('warehouse_order_detail_code');
            $table->integer('return_quantity');
            $table->integer('accepted_return_quantity')->nullable();
            $table->enum('status',['pending','accepted','rejected'])->default('pending');
            $table->enum('reason_type',['damaged','expired','others']);
            $table->longText('return_reason_remarks');
            $table->longText('status_remarks')->nullable();
            $table->string('status_responded_by')->nullable();
            $table->timestamp('status_responded_at')->nullable();
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();

            $table->primary(['warehouse_purchase_return_code'],'wpr_wprc_pk');
            $table->foreign('warehouse_order_code','wpr_woc_fk')
                ->references('warehouse_order_code')->on('warehouse_orders');
            $table->foreign('vendor_code','wpr_vc_fk')
                ->references('vendor_code')->on('vendors_detail');
            $table->foreign('warehouse_order_detail_code','wpr_wodc_fk')
                ->references('warehouse_order_detail_code')->on('warehouse_order_details');

            $table->foreign('status_responded_by')->references('user_code')->on('users');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table warehouse_purchase_return add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_purchase_return');
    }
}
