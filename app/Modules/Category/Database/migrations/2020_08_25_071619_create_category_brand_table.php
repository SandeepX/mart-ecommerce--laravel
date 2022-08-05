<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_brand', function (Blueprint $table) {
            $table->string('category_code');
            $table->string('brand_code');

            $table->foreign('category_code')->references('category_code')->on('category_master');
            $table->foreign('brand_code')->references('brand_code')->on('brands');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_brand');
    }
}
