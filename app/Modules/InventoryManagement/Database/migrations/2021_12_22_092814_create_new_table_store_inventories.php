<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewTableStoreInventories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('store_inventories', function (Blueprint $table) {

            $table->string('store_inventory_code')->primary();
            $table->string('store_code');
            $table->string('vendor_name');
            $table->string('product_code');
            $table->string('product_variant_code')->nullable();

            $table->string('created_by');
            $table->string('updated_by');

            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('store_code')->references('store_code')->on('stores_detail');
            $table->foreign('product_code')->references('product_code')->on('products_master');
            $table->foreign('product_variant_code')->references('product_variant_code')->on('product_variants');
        });

        DB::statement('ALTER Table store_inventories add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_inventories');
    }
}
