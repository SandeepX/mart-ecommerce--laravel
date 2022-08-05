<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWhDispatchRouteMarkersTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wh_dispatch_route_markers', function (Blueprint $table) {

            $table->string('wh_dispatch_route_marker_code');
            $table->string('wh_dispatch_route_code');
            $table->double('latitude');
            $table->double('longitude');
            $table->integer('sort_order');
            $table->boolean('is_store')->default(0);
            $table->string('created_by');
            $table->timestamps();

            $table->primary(['wh_dispatch_route_marker_code'],'pk_wdrm_wdrmc');
            $table->foreign('wh_dispatch_route_code','fk_wdrm_wdrc')->references('wh_dispatch_route_code')->on('warehouse_dispatch_routes');
            $table->foreign('created_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table wh_dispatch_route_markers add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wh_dispatch_route_markers');
    }
}
