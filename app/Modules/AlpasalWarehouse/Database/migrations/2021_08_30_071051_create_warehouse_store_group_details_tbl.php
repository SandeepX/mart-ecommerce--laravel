<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseStoreGroupDetailsTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_store_group_details', function (Blueprint $table) {
            $table->string('wh_store_group_detail_code');
            $table->string('wh_store_group_code');
            $table->string('store_code');
            $table->integer('sort_order');
            $table->boolean('is_active')->default(1);
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();

            $table->primary(['wh_store_group_detail_code'],'pk_wsgd_wsgdc');
            $table->foreign('wh_store_group_code','fk_wsgd_wsgc')->references('wh_store_group_code')->on('warehouse_store_groups');
            $table->foreign('store_code')->references('store_code')->on('stores_detail');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
        });

        DB::statement('ALTER Table warehouse_store_group_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_store_group_details');
    }
}
