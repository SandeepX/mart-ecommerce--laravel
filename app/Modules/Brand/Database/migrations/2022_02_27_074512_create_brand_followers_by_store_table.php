<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandFollowersByStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_followers_by_stores', function (Blueprint $table) {
            $table->string('brand_followers_by_store')->unique()->primary();
            $table->string('brand_code');
            $table->string('store_code');
//            $table->morphs('brand_followers_by_storeable');
            $table->softDeletes();

            $table->foreign('brand_code')->references('brand_code')->on('brands');
            $table->foreign('store_code')->references('store_code')->on('stores_detail');
            $table->timestamps();
        });
        DB::statement('ALTER Table brand_followers_by_stores add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brand_followers_by_stores');
    }
}
