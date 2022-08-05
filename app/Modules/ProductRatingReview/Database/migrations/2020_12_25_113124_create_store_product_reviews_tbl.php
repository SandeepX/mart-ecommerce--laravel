<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreProductReviewsTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_product_reviews', function (Blueprint $table) {
            $table->string('review_code')->unique()->primary();
            $table->string('warehouse_code');
            $table->string('product_code');
            $table->string('store_code');
            $table->string('user_code');
            $table->text('review_message');
            $table->boolean('is_active')->default(1);
            $table->text('remarks')->nullable();
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('warehouse_code')->references('warehouse_code')->on('warehouses');
            $table->foreign('store_code')->references('store_code')->on('stores_detail');
            $table->foreign('product_code')->references('product_code')->on('products_master');
            $table->foreign('user_code')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table store_product_reviews add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_product_reviews');
    }
}
