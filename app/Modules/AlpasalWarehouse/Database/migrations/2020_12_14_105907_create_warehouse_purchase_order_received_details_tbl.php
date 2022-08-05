<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousePurchaseOrderReceivedDetailsTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_purchase_order_received_details', function (Blueprint $table) {
            $table->string('warehouse_purchase_order_received_detail_code')->unique('uq_wpordc');
            $table->string('warehouse_order_detail_code');
            $table->boolean('has_received')->default(0);
            $table->integer('received_quantity')->nullable();
            $table->date('manufactured_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();
            $table->primary(['warehouse_purchase_order_received_detail_code'],'wpordc');

            $table->foreign('warehouse_order_detail_code','wpord_wodc')->references('warehouse_order_detail_code')->on('warehouse_order_details');
        });
        DB::statement('ALTER Table warehouse_purchase_order_received_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_purchase_order_received_details');
    }
}
