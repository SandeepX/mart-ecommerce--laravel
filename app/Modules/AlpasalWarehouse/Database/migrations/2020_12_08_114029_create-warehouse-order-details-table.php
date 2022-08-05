<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_order_details', function (Blueprint $table) {

            $table->string('warehouse_order_detail_code')->unique()->primary();
            $table->string('warehouse_order_code');
            $table->string('product_code');
            $table->string('product_variant_code')->nullable();
            $table->boolean('is_taxable_product')->default(0);
            $table->integer('quantity');
            $table->double('unit_rate');
            $table->string('mrp');
            $table->enum('admin_margin_type',['p','f']);
            $table->double('admin_margin_value');
            $table->enum('wholesale_margin_type',['p','f']);
            $table->double('wholesale_margin_value');
            $table->enum('retail_margin_type',['p','f']);
            $table->double('retail_margin_value');
            $table->boolean('has_received')->default(0);
            $table->integer('received_quantity')->nullable();

            $table->timestamps();

            $table->foreign('warehouse_order_code')->references('warehouse_order_code')->on('warehouse_orders');
            $table->foreign('product_code')->references('product_code')->on('products_master');
            $table->foreign('product_variant_code')->references('product_variant_code')->on('product_variants');
        });
        DB::statement('ALTER Table warehouse_order_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_order_details');
    }
}
