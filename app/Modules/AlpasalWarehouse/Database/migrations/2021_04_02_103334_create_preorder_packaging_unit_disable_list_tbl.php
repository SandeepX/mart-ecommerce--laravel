<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreorderPackagingUnitDisableListTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preorder_packaging_unit_disable_list', function (Blueprint $table) {
            $table->string('preorder_packaging_unit_disable_list_code');
            $table->string('warehouse_preorder_product_code');
            $table->enum('unit_name', ['micro','unit','macro','super']);
            $table->string('disabled_by');
            $table->timestamps();

            $table->primary(['preorder_packaging_unit_disable_list_code'], 'pk_ppudl_ppudlc');

            $table->foreign('disabled_by','fk_ppudl_db')->references('user_code')->on('users');
            $table->foreign('warehouse_preorder_product_code','fk_ppudl_wppc')
                ->references('warehouse_preorder_product_code')->on('warehouse_preorder_products');
        });

        DB::statement('ALTER Table preorder_packaging_unit_disable_list add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('preorder_packaging_unit_disable_list');
    }
}
