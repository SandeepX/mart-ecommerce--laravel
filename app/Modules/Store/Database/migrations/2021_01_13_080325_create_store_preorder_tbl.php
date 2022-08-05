<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorePreorderTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_preorder', function (Blueprint $table) {
            $table->string('store_preorder_code');
            $table->string('warehouse_preorder_listing_code');
            $table->string('store_code');
            $table->boolean('payment_status')->default(0);
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->primary(['store_preorder_code'],'pk_sp_spc');
            $table->foreign('warehouse_preorder_listing_code')->references('warehouse_preorder_listing_code')->on('warehouse_preorder_listings');
            $table->foreign('store_code')->references('store_code')->on('stores_detail');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
        });

        DB::statement('ALTER Table store_preorder add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_preorder');
    }
}
