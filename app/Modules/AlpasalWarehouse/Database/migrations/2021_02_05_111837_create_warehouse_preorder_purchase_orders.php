<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousePreorderPurchaseOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_preorder_purchase_orders', function (Blueprint $table) {
            $table->string('warehouse_preorder_purchase_order_code');
            $table->string('warehouse_order_code');
            $table->string('warehouse_preorder_listing_code');
            $table->timestamps();

            $table->primary(['warehouse_preorder_purchase_order_code'],'pk_wppo_wppoc');
            $table->foreign('warehouse_order_code','fk_wppo_woc')->references('warehouse_order_code')->on('warehouse_orders');
            $table->foreign('warehouse_preorder_listing_code','fk_wppo_wplc')->references('warehouse_preorder_listing_code')->on('warehouse_preorder_listings');
        });

        DB::statement('ALTER Table warehouse_preorder_purchase_orders add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_preorder_purchase_orders');
    }
}
