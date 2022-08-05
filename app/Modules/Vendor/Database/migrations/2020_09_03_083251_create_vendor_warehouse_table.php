<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorWarehouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors_warehouse', function (Blueprint $table) {
            $table->string('vendor_warehouse_code')->unique()->primary();
            $table->string('vendor_warehouse_name');
            $table->string('vendor_warehouse_location');
            $table->string('vendor_code');
            $table->string('landmark_name');
            $table->double('latitude');
            $table->double('longitude');
            $table->string('remarks')->nullable();
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
            $table->foreign('vendor_warehouse_location')->references('location_code')->on('location_hierarchy');
            $table->foreign('vendor_code')->references('vendor_code')->on('vendors_detail');
        });
        DB::statement('ALTER Table vendors_warehouse add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors_warehouse');
    }
}
