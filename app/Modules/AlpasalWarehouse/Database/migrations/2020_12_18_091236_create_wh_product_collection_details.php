<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWhProductCollectionDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wh_product_collection_details', function (Blueprint $table) {
            $table->string('warehouse_product_master_code');
            $table->string('product_collection_code');
            $table->boolean('is_active')->default(0);
            $table->string('created_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
            $table->foreign('warehouse_product_master_code','wpmc')->references('warehouse_product_master_code')->on('warehouse_product_master');
            $table->foreign('product_collection_code')->references('product_collection_code')->on('wh_product_collections');
        });
        DB::statement('ALTER Table wh_product_collection_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wh_product_collection_details');
    }
}
