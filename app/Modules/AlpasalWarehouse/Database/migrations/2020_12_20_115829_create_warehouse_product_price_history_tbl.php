<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseProductPriceHistoryTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_product_price_history', function (Blueprint $table) {

            $table->string('warehouse_product_price_history_code')->unique('wpph_wppc_uq');
            $table->string('warehouse_product_master_code');
            $table->double('mrp');
            $table->enum('admin_margin_type',['p','f']);
            $table->double('admin_margin_value');
            $table->enum('wholesale_margin_type', ['p', 'f']);
            $table->double('wholesale_margin_value');
            $table->enum('retail_margin_type',['p', 'f']);
            $table->double('retail_margin_value');

            $table->date('from_date');
            $table->date('to_date')->nullable();
            $table->timestamps();

            $table->primary(['warehouse_product_price_history_code'],'wpph_wpphc_pk');
            $table->foreign('warehouse_product_master_code','wpph_wpmc')->references('warehouse_product_master_code')->on('warehouse_product_master');
        });

        DB::statement('ALTER Table warehouse_product_price_history add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_product_price_history');
    }
}
