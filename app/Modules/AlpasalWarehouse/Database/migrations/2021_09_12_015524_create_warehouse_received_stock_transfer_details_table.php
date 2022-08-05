<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateWarehouseReceivedStockTransferDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_received_stock_transfer_details', function (Blueprint $table) {
            $table->string('received_stock_transfer_details_code');
            $table->string('stock_transfer_master_code');
            $table->string('warehouse_product_master_code');
            $table->integer('received_quantity');
            $table->integer('package_quantity');
            $table->string('package_code')->nullable();
            $table->string('product_packaging_history_code')->nullable();
            $table->string('created_by');
            $table->softDeletes();
            $table->timestamps();

            $table->primary(['received_stock_transfer_details_code'],'pk_wrstd_rstdc');

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('package_code')->references('package_code')->on('package_types');
            $table->foreign('product_packaging_history_code','fk_wrstd_pph')->references('product_packaging_history_code')->on('product_packaging_history');
            $table->foreign('stock_transfer_master_code','fk_wrstd_stm')->references('stock_transfer_master_code')->on('warehouse_stock_transfer_master');
            $table->foreign('warehouse_product_master_code','fk_wrstd_wpm')->references('warehouse_product_master_code')->on('warehouse_product_master');
        });

        DB::statement('ALTER Table warehouse_received_stock_transfer_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_received_stock_transfer_details');
    }
}
