<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWhProductCollections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wh_product_collections', function (Blueprint $table) {
            $table->string('product_collection_code')->unique()->primary();
            $table->string('warehouse_code');
            $table->string('product_collection_title');
            $table->string('product_collection_slug');
            $table->string('product_collection_subtitle');
            $table->string('product_collection_image')->nullable();
            $table->text('remarks')->nullable();
            $table->boolean('is_active')->default(0);
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
            $table->foreign('warehouse_code')->references('warehouse_code')->on('warehouses');
        });
        DB::statement('ALTER Table wh_product_collections add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wh_product_collections');
    }
}
