<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorePreorderDetailsTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_preorder_details', function (Blueprint $table) {

            $table->string('store_preorder_detail_code');
            $table->string('store_preorder_code');
            $table->string('warehouse_preorder_product_code');
            $table->integer('quantity');
            $table->boolean('is_taxable')->default(0);
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->primary(['store_preorder_detail_code'],'pk_spd_spdc');
            $table->foreign('store_preorder_code','fk_spd_spc')->references('store_preorder_code')->on('store_preorder');
            $table->foreign('warehouse_preorder_product_code','fk_spd_wppc')->references('warehouse_preorder_product_code')->on('warehouse_preorder_products');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
        });

        DB::statement('ALTER Table store_preorder_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_preorder_details');
    }
}
