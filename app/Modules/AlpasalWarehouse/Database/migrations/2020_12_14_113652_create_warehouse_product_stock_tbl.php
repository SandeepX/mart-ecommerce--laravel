<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseProductStockTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_product_stock', function (Blueprint $table) {
            $table->string('warehouse_product_stock_code')->unique()->primary();
            $table->string('warehouse_product_master_code');
            $table->integer('quantity');
            $table->enum('action',['purchase','sales','purchase-return','stock-transfer','sales-return']);
            $table->timestamps();

            $table->foreign('warehouse_product_master_code')->references('warehouse_product_master_code')->on('warehouse_product_master');
        });
        DB::statement('ALTER Table warehouse_product_stock add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_product_stock');
    }
}
