<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousePreorderTargetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preorder_target', function (Blueprint $table) {
            $table->string('preorder_target_code');
            $table->string('warehouse_preorder_listing_code');
            $table->string('store_type_code');
            $table->enum('target_type',['group','individual']);
            $table->string('target_value')->nullable();
            $table->string('created_by');
            $table->softDeletes();
            $table->timestamps();

            $table->primary('preorder_target_code');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('warehouse_preorder_listing_code','fk_wplc')->references('warehouse_preorder_listing_code')->on('warehouse_preorder_listings');
            $table->foreign('store_type_code')->references('store_type_code')->on('store_types');
        });

        DB::statement('ALTER Table preorder_target add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('preorder_target');
    }
}
