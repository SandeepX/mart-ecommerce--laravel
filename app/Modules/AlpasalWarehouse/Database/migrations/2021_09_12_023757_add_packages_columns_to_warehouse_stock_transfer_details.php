<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPackagesColumnsToWarehouseStockTransferDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_stock_transfer_details', function (Blueprint $table) {
            $table->integer('package_quantity')->after('sending_quantity')->nullable();
            $table->string('package_code')->after('package_quantity')->nullable();
            $table->string('product_packaging_history_code')->after('package_code')->nullable();

            $table->foreign('package_code')->references('package_code')->on('package_types');
            $table->foreign('product_packaging_history_code','fk_wstd_pph')->references('product_packaging_history_code')
                ->on('product_packaging_history');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_stock_transfer_details', function (Blueprint $table) {
            $table->dropColumn(['package_quantity','package_code','product_packaging_history_code']);
        });
    }
}
