<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousePreorderProductsTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_preorder_products', function (Blueprint $table) {

            $table->string('warehouse_preorder_product_code');
            $table->string('warehouse_preorder_listing_code');
            $table->string('product_code');
            $table->string('product_variant_code')->nullable();
            $table->double('mrp');
            $table->enum('admin_margin_type',['p','f'])->default('f');
            $table->double('admin_margin_value');
            $table->enum('wholesale_margin_type', ['p', 'f'])->default('f');
            $table->double('wholesale_margin_value');
            $table->enum('retail_margin_type',['p', 'f'])->default('f');
            $table->double('retail_margin_value');
            $table->boolean('is_active')->default(0);
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->primary(['warehouse_preorder_product_code'],'pk_wpp_wppc');
            $table->foreign('warehouse_preorder_listing_code','fk_wpp_wplc')->references('warehouse_preorder_listing_code')->on('warehouse_preorder_listings');
            $table->foreign('product_code')->references('product_code')->on('products_master');
            $table->foreign('product_variant_code')->references('product_variant_code')->on('product_variants');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
        });

        DB::statement('ALTER Table warehouse_preorder_products add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_preorder_products');
    }
}
