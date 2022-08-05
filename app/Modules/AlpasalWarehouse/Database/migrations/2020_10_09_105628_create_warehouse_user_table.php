<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_user', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('warehouse_code');
            $table->string('user_code');
            $table->timestamps();

            $table->foreign('warehouse_code')->references('warehouse_code')->on('warehouses')->onDelete('cascade');
            $table->foreign('user_code')->references('user_code')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_user');
    }
}
