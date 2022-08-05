<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCollectionDetailsTable extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_collection_details', function (Blueprint $table) {
            $table->string('product_code');
            $table->string('product_collection_code');
            $table->boolean('is_active')->default(1);
            $table->string('created_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
            $table->foreign('product_code')->references('product_code')->on('products_master');
            $table->foreign('product_collection_code')->references('product_collection_code')->on('product_collections');
        });
        DB::statement('ALTER Table product_collection_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT First');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_collection_details');
    }
}
