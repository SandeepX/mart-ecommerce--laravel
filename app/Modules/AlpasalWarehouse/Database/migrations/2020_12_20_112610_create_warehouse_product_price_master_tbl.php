<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseProductPriceMasterTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_product_price_master', function (Blueprint $table) {

            $table->string('warehouse_product_price_code')->unique('wppc_uq');
            $table->string('warehouse_product_master_code');
            $table->double('mrp');
            $table->enum('admin_margin_type',['p','f']);
            $table->double('admin_margin_value');
            $table->enum('wholesale_margin_type', ['p', 'f']);
            $table->double('wholesale_margin_value');
            $table->enum('retail_margin_type',['p', 'f']);
            $table->double('retail_margin_value');

            $table->string('created_by');
            $table->string('updated_by');

            $table->timestamps();

            $table->primary(['warehouse_product_price_code'],'wppc_pk');
            $table->foreign('warehouse_product_master_code','wppm_wpmc')->references('warehouse_product_master_code')->on('warehouse_product_master');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
        });

        DB::statement('ALTER Table warehouse_product_price_master add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_product_price_master');
    }
}
