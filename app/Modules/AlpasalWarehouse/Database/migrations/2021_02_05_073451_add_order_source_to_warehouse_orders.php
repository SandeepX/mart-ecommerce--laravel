<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderSourceToWarehouseOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_orders', function (Blueprint $table) {
            //
            $table->enum('order_source',['normal','preorder'])->default('normal')->after('warehouse_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_orders', function (Blueprint $table) {
            //
            $table->dropColumn('order_source');
        });
    }
}
