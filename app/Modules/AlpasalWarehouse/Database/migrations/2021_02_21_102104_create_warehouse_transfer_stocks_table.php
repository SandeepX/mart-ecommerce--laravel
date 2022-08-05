<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseTransferStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_transfer_stocks', function (Blueprint $table) {
            $table->string('warehouse_transfer_stock_code');
            $table->string('warehouse_product_stock_code');
            $table->string('stock_transfer_master_code');
            $table->softDeletes();
            $table->timestamps();

            $table->primary(['warehouse_transfer_stock_code'], 'wh_trn_stck');
            $table->foreign('warehouse_product_stock_code')->references('warehouse_product_stock_code')->on('warehouse_product_stock');
            $table->foreign('stock_transfer_master_code')->references('stock_transfer_master_code')->on('warehouse_stock_transfer_master');
        });

        DB::statement('ALTER Table warehouse_transfer_stocks add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_transfer_stocks');
    }
}
