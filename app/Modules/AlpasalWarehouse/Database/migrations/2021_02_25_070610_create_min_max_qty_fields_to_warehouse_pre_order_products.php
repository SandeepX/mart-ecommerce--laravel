<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinMaxQtyFieldsToWarehousePreOrderProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_preorder_products', function (Blueprint $table) {
            $table->integer('min_order_quantity')->nullable()->after('product_variant_code');
            $table->integer('max_order_quantity')->nullable()->after('min_order_quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_preorder_products', function (Blueprint $table) {
            $table->dropColumn('min_order_quantity');
            $table->dropColumn('max_order_quantity');
        });
    }
}
