<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->string('cart_code')->unique()->primary();
            $table->string('product_code');
            $table->string('product_variant_code')->nullable();
            $table->integer('quantity');
            $table->string('user_code');
            $table->timestamps();

            $table->foreign('product_code')->references('product_code')->on('products_master');
            $table->foreign('product_variant_code')->references('product_variant_code')->on('product_variants');
            $table->foreign('user_code')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table carts add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST ');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
