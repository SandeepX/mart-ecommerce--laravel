<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPackageQtyPackageHistoryWithReferenceCodeToWarehouseProductStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_product_stock', function (Blueprint $table) {
            $table->integer('package_qty')->after('quantity')->nullable();
            $table->string('package_code')->after('package_qty')->nullable();
            $table->string('product_packaging_history_code')->after('package_code')->nullable();
            $table->string('reference_code')->after('product_packaging_history_code')->nullable();

            $table->foreign('package_code')->references('package_code')->on('package_types');
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
        Schema::table('warehouse_product_stock', function (Blueprint $table) {
            $table->dropColumn(['package_qty','package_code','product_packaging_history_code','reference_code']);
        });
    }
}
