<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseSalesReturnStocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_sales_return_stocks', function (Blueprint $table) {
            $table->string('warehouse_sales_return_stock_code');
            $table->string('warehouse_product_stock_code');
            $table->string('store_order_code');
            $table->longText('remarks')->nullable();

            $table->primary(['warehouse_sales_return_stock_code'],'wsrsc_primary');
            $table->foreign('warehouse_product_stock_code','fk_wsrs_wpsc')->references('warehouse_product_stock_code')->on('warehouse_product_stock');
            $table->foreign('store_order_code')->references('store_order_code')->on('store_orders');

            $table->timestamps();
        });

        DB::statement('ALTER Table warehouse_sales_return_stocks add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_sales_return_stocks');
    }
}
