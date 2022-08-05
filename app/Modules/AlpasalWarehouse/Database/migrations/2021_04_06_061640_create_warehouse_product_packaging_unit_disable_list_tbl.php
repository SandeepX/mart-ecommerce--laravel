<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseProductPackagingUnitDisableListTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_product_packaging_unit_disable_list', function (Blueprint $table) {

            $table->string('warehouse_product_packaging_unit_disable_list_code');
            $table->string('warehouse_product_master_code');
            $table->enum('unit_name', ['micro','unit','macro','super']);
            $table->string('disabled_by');
            $table->timestamps();

            $table->primary(['warehouse_product_packaging_unit_disable_list_code'], 'pk_wppudl_wppudlc');

            $table->foreign('disabled_by','fk_wppudl_db')->references('user_code')->on('users');
            $table->foreign('warehouse_product_master_code','fk_wppudl_wpmc')
                ->references('warehouse_product_master_code')->on('warehouse_product_master');
        });

        DB::statement('ALTER Table warehouse_product_packaging_unit_disable_list add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_product_packaging_unit_disable_list');
    }
}
