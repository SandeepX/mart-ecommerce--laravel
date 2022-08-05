<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->string('warehouse_code')->unique()->primary();
            $table->string('warehouse_name');
            $table->string('warehouse_type_code');
            $table->string('slug')->unique()->index();
            $table->string('location_code');
            $table->string('remarks')->nullable();
            $table->string('landmark_name');
            $table->double('latitude');
            $table->double('longitude');
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
            $table->foreign('warehouse_type_code')->references('warehouse_type_code')->on('alpasal_warehouse_types');
            $table->foreign('location_code')->references('location_code')->on('location_hierarchy');
        });
        DB::statement('ALTER Table warehouses add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouses');
    }
}
