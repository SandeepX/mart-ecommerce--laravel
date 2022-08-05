<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewTableStoreInventoryItemDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_inventory_item_detail', function (Blueprint $table) {

            $table->string('siid_code')->primary();
            $table->string('store_inventory_code');
            $table->double('cost_price');
            $table->double('mrp');
            $table->date('manufacture_date');
            $table->date('expiry_date');
            $table->string('signature');

            $table->string('created_by');
            $table->string('updated_by');

            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('store_inventory_code')->references('store_inventory_code')->on('store_inventories');

        });

        DB::statement('ALTER Table store_inventory_item_detail add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_inventory_item_detail');
    }
}
