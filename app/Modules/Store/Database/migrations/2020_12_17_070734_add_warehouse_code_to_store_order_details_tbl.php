<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWarehouseCodeToStoreOrderDetailsTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_order_details', function (Blueprint $table) {
            //
            $table->string('warehouse_code')->nullable()->after('store_order_code');

            $table->foreign('warehouse_code')->references('warehouse_code')->on('warehouses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_order_details', function (Blueprint $table) {
            //
        });
    }
}
