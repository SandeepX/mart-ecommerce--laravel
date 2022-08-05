<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousePreorderStockTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_preorder_stock', function (Blueprint $table) {
            $table->string('warehouse_preorder_stock_code');
            $table->string('warehouse_product_stock_code');
            $table->string('warehouse_preorder_listing_code');
            $table->string('store_preorder_detail_code');
            $table->timestamps();

            $table->primary(['warehouse_preorder_stock_code'],'pk_wprs_wprsc');
            $table->foreign('warehouse_product_stock_code','fk_wprs_wpsc')->references('warehouse_product_stock_code')->on('warehouse_product_stock');
            $table->foreign('warehouse_preorder_listing_code','fk_wprs_wplc')->references('warehouse_preorder_listing_code')->on('warehouse_preorder_listings');
            $table->foreign('store_preorder_detail_code','fk_wprs_spdc')->references('store_preorder_detail_code')->on('store_preorder_details');
        });

        DB::statement('ALTER Table warehouse_preorder_stock add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_preorder_stock', function (Blueprint $table) {
            //
        });
    }
}
