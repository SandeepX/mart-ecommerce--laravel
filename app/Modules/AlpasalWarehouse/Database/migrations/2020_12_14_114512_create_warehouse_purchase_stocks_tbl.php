<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousePurchaseStocksTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_purchase_stocks', function (Blueprint $table) {
            $table->string('warehouse_purchase_stock_code')->unique()->primary();
            $table->string('warehouse_product_stock_code');
            $table->string('warehouse_order_code');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('warehouse_product_stock_code')->references('warehouse_product_stock_code')->on('warehouse_product_stock');
            $table->foreign('warehouse_order_code')->references('warehouse_order_code')->on('warehouse_orders');


        });

        DB::statement('ALTER Table warehouse_purchase_stocks add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_purchase_stocks');
    }
}
