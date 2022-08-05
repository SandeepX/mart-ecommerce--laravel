<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableStoreInventoryItemDispatchedQtyDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_inventory_item_dispatched_qty_detail', function (Blueprint $table) {

            $table->string('siidqd_code')->primary();
            $table->string('siid_code');
            $table->string('package_code');
            $table->string('pph_code');
            $table->double('quantity');
            $table->double('micro_unit_quantity');
            $table->double('selling_price');
            $table->enum('payment_type',['credit','bank','cash'])->default('cash');

            $table->string('created_by');
            $table->string('updated_by');
            $table->softDeletes();
            $table->string('deleted_by')->nullable();
            $table->timestamps();


            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('deleted_by')->on('users');
            $table->foreign('package_code')->references('package_code')->on('package_types');
            $table->foreign('siid_code')->references('siid_code')->on('store_inventory_item_detail');
            $table->foreign('pph_code')->references('product_packaging_history_code')->on('product_packaging_history');
        });

        DB::statement('ALTER Table store_inventory_item_dispatched_qty_detail add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_inventory_item_dispatched_qty_detail');
    }
}
