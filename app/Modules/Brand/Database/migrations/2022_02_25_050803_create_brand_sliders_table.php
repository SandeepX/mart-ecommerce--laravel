<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateBrandSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_sliders', function (Blueprint $table) {
            $table->string('brand_slider_code')->unique()->primary();
            $table->string('image');
            $table->string('brand_code');
            $table->boolean('is_active');
            $table->string('description')->nullable();
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('brand_code')->references('brand_code')->on('brands');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table brand_sliders add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brand_sliders');
    }
}
