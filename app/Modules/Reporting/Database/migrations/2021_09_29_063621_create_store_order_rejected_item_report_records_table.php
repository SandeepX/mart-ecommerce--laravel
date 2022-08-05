<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreOrderRejectedItemReportRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_order_rejected_item_report', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('store_order_detail_code');
            $table->string('store_order_code');
            $table->string('warehouse_code');
            $table->string('product_code');
            $table->string('product_variant_code')->nullable();
            $table->string('package_code')->nullable();
            $table->string('product_packaging_history_code')->nullable();
            $table->integer('quantity');
            $table->double('unit_rate');
            $table->text('remark');
            $table->dateTime('store_order_updated_at');
            $table->timestamps();

            $table->foreign('store_order_detail_code')->references('store_order_detail_code')->on('store_order_details');
            $table->foreign('store_order_code')->references('store_order_code')->on('store_orders');
            $table->foreign('warehouse_code')->references('warehouse_code')->on('warehouses');
            $table->foreign('product_code')->references('product_code')->on('products_master');
            $table->foreign('product_variant_code')->references('product_variant_code')->on('product_variants');
            $table->foreign('package_code')->references('package_code')->on('package_types');
            $table->foreign('product_packaging_history_code','pph')->references('product_packaging_history_code')->on('product_packaging_history');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_order_rejected_item_report');
    }
}
