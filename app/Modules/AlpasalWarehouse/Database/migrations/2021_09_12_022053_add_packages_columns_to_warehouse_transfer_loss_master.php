<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPackagesColumnsToWarehouseTransferLossMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_transfer_loss_master', function (Blueprint $table) {
           $table->string('warehouse_product_master_code')->after('stock_transfer_master_code')->nullable();
           $table->integer('package_quantity')->after('quantity')->nullable();
           $table->string('package_code')->after('package_quantity')->nullable();
           $table->string('product_packaging_history_code')->after('package_code')->nullable();

           $table->foreign('warehouse_product_master_code','fk_wtlm_wpm')->references('warehouse_product_master_code')
                                                                    ->on('warehouse_product_master');
           $table->foreign('package_code')->references('package_code')->on('package_types');
           $table->foreign('product_packaging_history_code' ,'fk_wtlm_pph')->references('product_packaging_history_code')
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
        Schema::table('warehouse_transfer_loss_master', function (Blueprint $table) {
            $table->dropColumn(['warehouse_product_master_code','package_quantity','package_code','product_packaging_history_code']);
        });
    }
}
