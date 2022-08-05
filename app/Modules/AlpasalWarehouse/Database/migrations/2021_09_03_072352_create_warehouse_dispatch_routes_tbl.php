<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseDispatchRoutesTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_dispatch_routes', function (Blueprint $table) {

            $table->string('wh_dispatch_route_code');
            $table->string('warehouse_code');
            $table->string('route_name');
            $table->string('vehicle_name')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_contact_primary')->nullable();
            $table->string('driver_contact_secondary')->nullable();
            $table->text('description')->nullable();
            $table->enum('status',['pending','dispatched'])->default('pending');
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();

            $table->primary(['wh_dispatch_route_code'],'pk_wdr_wdrc');
            $table->foreign('warehouse_code')->references('warehouse_code')->on('warehouses');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table warehouse_dispatch_routes add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_dispatch_routes');
    }
}
