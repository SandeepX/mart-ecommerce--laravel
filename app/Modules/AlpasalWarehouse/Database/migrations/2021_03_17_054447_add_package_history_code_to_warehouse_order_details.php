<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPackageHistoryCodeToWarehouseOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_order_details', function (Blueprint $table) {
            $table->string('product_packaging_history_code')->after('package_code')->nullable();


            $table->foreign('product_packaging_history_code')->references('product_packaging_history_code')->on('product_packaging_history');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_order_details', function (Blueprint $table) {
            $table->dropColumn('product_packaging_history_code');
        });
    }
}
