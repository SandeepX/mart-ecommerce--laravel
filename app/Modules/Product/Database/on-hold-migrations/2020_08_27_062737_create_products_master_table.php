<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_master', function (Blueprint $table) {
            $table->string('product_code')->unique()->primary();
            $table->string('product_name');
            $table->longText('description');
            $table->string('vendor_code');
            $table->string('brand_code');
            $table->string('category_code');
            $table->string('sensitivity_code');
            $table->string('remarks')->nullable();
            //$table->boolean('variant_tag');
            $table->enum('cetegory_type_code',['essential', 'non essential']);
            $table->string('sku');
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
            $table->foreign('vendor_code')->references('vendor_code')->on('vendors_detail');
            $table->foreign('brand_code')->references('brand_code')->on('brands');
            $table->foreign('category_code')->references('category_code')->on('category_master');
            $table->foreign('sensitivity_code')->references('sensitivity_code')->on('product_sensitivities');
            
        });
        DB::statement('ALTER Table products_master add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT First');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_master');
    }
}
