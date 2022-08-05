<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseStockTransferMasterMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_stock_transfer_master_meta', function (Blueprint $table) {
            $table->string('warehouse_stock_transfer_master_meta_code');
            $table->string('stock_transfer_master_code');
            $table->string('key');
            $table->string('value');
            $table->boolean('is_active');
            $table->timestamps();

            $table->primary(['warehouse_stock_transfer_master_meta_code'], 'wh_st_mm_cd');
            $table->foreign('stock_transfer_master_code', 'wh_st_mcd')->references('stock_transfer_master_code')->on('warehouse_stock_transfer_master');
        });

        DB::statement('ALTER Table warehouse_stock_transfer_master_meta add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_stock_transfer_master_meta');
    }
}
