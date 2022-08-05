<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseSalesStocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_sales_stocks', function (Blueprint $table) {
            $table->string('warehouse_sales_stock_code')->unique('uq_wssc');
            $table->primary(['warehouse_sales_stock_code'],'wssc_primary');
            $table->string('store_order_code');
            $table->string('warehouse_product_stock_code');
            $table->longText('remarks')->nullable();

            $table->foreign('store_order_code')->references('store_order_code')->on('store_orders');
            $table->foreign('warehouse_product_stock_code')->references('warehouse_product_stock_code')->on('warehouse_product_stock');

            $table->timestamps();
        });

        DB::statement('ALTER Table warehouse_sales_stocks add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_sales_stocks');
    }
}
