<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductWarrantyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_warranty_details', function (Blueprint $table) {
            $table->string('product_warranty_detail_code')->unique()->primary();
            $table->string('product_code');
            $table->string('warranty_code');
            $table->longText('warranty_policy');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('product_code')->references('product_code')->on('products_master');
            $table->foreign('warranty_code')->references('warranty_code')->on('product_warranties');
        });
        DB::statement('ALTER Table product_warranty_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT First');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_warranty_details');
    }
}
