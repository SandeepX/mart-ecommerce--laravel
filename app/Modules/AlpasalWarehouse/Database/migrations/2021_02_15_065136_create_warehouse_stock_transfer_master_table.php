<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseStockTransferMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_stock_transfer_master', function (Blueprint $table) {
            $table->string('stock_transfer_master_code');
            $table->enum('status', ['draft', 'sent', 'received']);
            $table->string('created_by');
            $table->longText('remarks')->nullable();
            $table->string('source_warehouse_code');
            $table->string('destination_warehouse_code');
            $table->softDeletes();
            $table->timestamps();

            $table->primary(['stock_transfer_master_code'], 'pk_sttns_mt');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('source_warehouse_code')->references('warehouse_code')->on('warehouses');
            $table->foreign('destination_warehouse_code', 'fk_ds_whcd')->references('warehouse_code')->on('warehouses');
        });
        DB::statement('ALTER Table warehouse_stock_transfer_master add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_stock_transfer_master');
    }
}
