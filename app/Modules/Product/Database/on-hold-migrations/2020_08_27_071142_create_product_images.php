<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->string('product_image_code')->unique()->primary();
            $table->string('product_code');
            $table->string('image');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('product_code')->references('product_code')->on('products_master');
        });
        DB::statement('ALTER Table product_images add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT First');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_images');
    }
}
