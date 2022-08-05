<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductVariantImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variant_images', function (Blueprint $table) {
            $table->string('product_variant_image_code')->unique()->primary();
            $table->string('product_code');
            $table->string('product_variant_code');
            $table->string('image');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('product_code')->references('product_code')->on('products_master');
            $table->foreign('product_variant_code')->references('product_variant_code')->on('product_variants');
        });
        DB::statement('ALTER Table product_variant_images add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT First');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variant_images');
    }
}
