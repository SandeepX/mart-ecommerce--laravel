<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseProductMasterTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_product_master', function (Blueprint $table) {

            $table->string('warehouse_product_master_code')->unique()->primary();
            $table->string('warehouse_code');
            $table->string('product_code');
            $table->string('product_variant_code')->nullable();
            $table->string('vendor_code');
            $table->boolean('is_active')->default(1);
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();

            $table->foreign('warehouse_code')->references('warehouse_code')->on('warehouses');
            $table->foreign('product_code')->references('product_code')->on('products_master');
            $table->foreign('product_variant_code')->references('product_variant_code')->on('product_variants');
            $table->foreign('vendor_code')->references('vendor_code')->on('vendors_detail');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table warehouse_product_master add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_product_master');
    }
}
