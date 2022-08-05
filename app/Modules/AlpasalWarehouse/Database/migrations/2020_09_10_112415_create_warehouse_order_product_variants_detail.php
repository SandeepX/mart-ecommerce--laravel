<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseOrderProductVariantsDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_order_product_variants_detail', function (Blueprint $table) {
            $table->string('order_product_detail_code')->unique()->primary('order_detail_code');
            $table->string('order_code');
            $table->string('product_code');
            $table->string('product_variant_code');
            $table->integer('package_quantity');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('order_code')->references('order_code')->on('warehouse_order_placement_by_admin');
            $table->foreign('product_code')->references('product_code')->on('products_master');
            $table->foreign('product_variant_code','product_variant_code_foreign')->references('product_variant_code')->on('product_variants');
        });
        DB::statement('ALTER Table warehouse_order_product_variants_detail add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_order_product_variants_detail');
    }
}
