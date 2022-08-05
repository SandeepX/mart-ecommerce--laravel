<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreWarehouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_warehouse', function (Blueprint $table) {
            $table->string('store_code');
            $table->string('warehouse_code');
            $table->boolean('connection_status')->default(1);
            $table->timestamps();

            $table->primary(['store_code','warehouse_code']);

            $table->foreign('store_code')->references('store_code')->on('stores_detail');
            $table->foreign('warehouse_code')->references('warehouse_code')->on('warehouses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_warehouse');
    }
}
