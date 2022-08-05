<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCurrentStockToWarehouseProductMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_product_master', function (Blueprint $table) {
            $table->bigInteger('current_stock')->default(0)->after('max_order_quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_product_master', function (Blueprint $table) {
            $table->dropColumn('current_stock');
        });
    }
}
