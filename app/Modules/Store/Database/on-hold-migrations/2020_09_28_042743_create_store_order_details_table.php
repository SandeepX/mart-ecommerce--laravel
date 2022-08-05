<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateStoreOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_order_details', function (Blueprint $table) {
            $table->string('store_order_detail_code')->unique()->primary();
            $table->string('store_order_code');
            $table->string('product_code');
            $table->string('product_variant_code')->nullable();
            $table->integer('quantity');
            $table->double('unit_rate');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('store_order_code')->references('store_order_code')->on('store_orders');
            $table->foreign('product_code')->references('product_code')->on('products_master');
            $table->foreign('product_variant_code')->references('product_variant_code')->on('product_variants');

        });
        DB::statement('ALTER Table store_order_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_order_details');
    }
}
