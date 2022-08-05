<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateProductPriceLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_price_lists', function (Blueprint $table) {
            $table->string('product_price_list_code')->unique()->primary();
            $table->string('product_code');
            $table->string('product_variant_code')->nullable();
            $table->float('mrp');
            $table->enum('admin_margin_type',['p', 'f']);
            $table->float('admin_margin_value');
            $table->enum('wholesale_margin_type', ['p', 'f']);
            $table->float('wholesale_margin_value');
            $table->enum('retail_store_margin_type',['p', 'f']);
            $table->float('retail_store_margin_value');
            $table->softDeletes();
            $table->timestamps();
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
            $table->foreign('product_code')->references('product_code')->on('products_master');
            $table->foreign('product_variant_code')->references('product_variant_code')->on('product_variants');

        });
        DB::statement('ALTER Table product_price_lists add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST ');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_price_list_table');
    }
}
