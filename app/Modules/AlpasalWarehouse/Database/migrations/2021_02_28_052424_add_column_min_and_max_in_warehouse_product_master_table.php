<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnMinAndMaxInWarehouseProductMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_product_master', function (Blueprint $table) {
            $table->integer('min_order_quantity')->nullable()->after('is_active');
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
        Schema::table('warehouse_product_master', function (Blueprint $table) {
            $table->dropColumn('min_order_quantity');
            $table->dropColumn('max_order_quantity');
        });
    }
}
