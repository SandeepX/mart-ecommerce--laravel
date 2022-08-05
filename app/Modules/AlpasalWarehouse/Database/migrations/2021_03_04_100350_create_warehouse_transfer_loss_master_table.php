<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseTransferLossMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_transfer_loss_master', function (Blueprint $table) {
            $table->string('warehouse_stock_transfer_loss_master_code');
            $table->string('stock_transfer_master_code');
            $table->string('stock_transfer_details_code');
            $table->integer('quantity');
            $table->enum('reason', ['transfer-loss']);
            $table->timestamps();

            $table->primary(['warehouse_stock_transfer_loss_master_code'], 'wh_st_lss_cd');
            $table->foreign('stock_transfer_master_code', 'st_mst_cd')->references('stock_transfer_master_code')->on('warehouse_stock_transfer_master');
            $table->foreign('stock_transfer_details_code', 'st_dtls_cd')->references('stock_transfer_details_code')->on('warehouse_stock_transfer_details');
        });
        DB::statement('ALTER Table warehouse_transfer_loss_master add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_transfer_loss_master');
    }
}
