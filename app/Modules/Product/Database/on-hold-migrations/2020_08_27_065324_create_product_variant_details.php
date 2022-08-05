<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductVariantDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variant_details', function (Blueprint $table) {
            $table->string('product_variant_detail_code')->unique()->primary();
            $table->string('product_variant_code');
            $table->string('variant_value_code');
            $table->string('remarks')->nullable();
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
            $table->foreign('product_variant_code')->references('product_variant_code')->on('product_variants');
            $table->foreign('variant_value_code')->references('variant_value_code')->on('variant_values');
        });
        DB::statement('ALTER Table product_variant_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT First');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variant_details');
    }
}
