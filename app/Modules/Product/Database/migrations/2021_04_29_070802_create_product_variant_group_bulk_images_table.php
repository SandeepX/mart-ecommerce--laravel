<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductVariantGroupBulkImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pv_group_bulk_images', function (Blueprint $table) {
            $table->string('pv_group_bulk_image_code')->unique()->primary();
            $table->string('product_variant_group_code');
            $table->string('image');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('product_variant_group_code')->references('product_variant_group_code')->on('product_variant_groups');
        });

        DB::statement('ALTER Table pv_group_bulk_images add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pv_group_bulk_images_table');
    }
}
