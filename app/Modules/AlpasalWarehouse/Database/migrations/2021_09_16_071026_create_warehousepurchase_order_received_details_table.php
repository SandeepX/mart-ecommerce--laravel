<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousepurchaseOrderReceivedDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_purchase_order_received_details', function (Blueprint $table) {
            $table->string('warehouse_purchase_order_received_detail_code');
            $table->string('warehouse_order_code');
            $table->string('product_code');
            $table->string('product_variant_code')->nullable();
            $table->integer('received_quantity');
            $table->integer('package_quantity')->nullable();
            $table->string('package_code')->nullable();
            $table->string('product_packaging_history_code')->nullable();
            $table->tinyInteger('has_received')->default(0);
            $table->date('manufactured_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();

            $table->primary('warehouse_purchase_order_received_detail_code','pk_wpordv1_wpordc');
            $table->foreign('warehouse_order_code','fk_wpordv1_woc')->references('warehouse_order_code')->on('warehouse_orders');
            $table->foreign('product_code','fk_wpordv1_pc')->references('product_code')->on('products_master');
            $table->foreign('product_variant_code','fk_wpordv1_pvc')->references('product_variant_code')->on('product_variants');
            $table->foreign('package_code','fk_wpordv1_pt')->references('package_code')->on('package_types');
            $table->foreign('product_packaging_history_code','fk_wpordv1_pphc')->references('product_packaging_history_code')
                ->on('product_packaging_history');
        });
        DB::statement('ALTER Table warehouse_purchase_order_received_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_purchase_order_received_details');
    }
}
