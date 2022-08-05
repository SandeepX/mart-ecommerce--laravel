<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseStockTransferDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_stock_transfer_details', function (Blueprint $table) {
            $table->string('stock_transfer_details_code');
            $table->string('stock_transfer_master_code');
            $table->string('warehouse_product_master_code');
            $table->integer('sending_quantity');
            $table->integer('received_quantity')->nullable();
            $table->string('created_by');
            $table->softDeletes();
            $table->timestamps();

            $table->primary(['stock_transfer_details_code'], 'sk_trns_dt');
            $table->foreign('stock_transfer_master_code', 'fk_sttr_mst')->references('stock_transfer_master_code')->on('warehouse_stock_transfer_master');
            $table->foreign('warehouse_product_master_code', 'fk_whpd_mst')->references('warehouse_product_master_code')->on('warehouse_product_master');
            $table->foreign('created_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table warehouse_stock_transfer_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_stock_transfer_details');
    }
}
