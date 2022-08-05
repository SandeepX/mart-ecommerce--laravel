<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWhDispatchRouteStoresTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wh_dispatch_route_stores', function (Blueprint $table) {

            $table->string('wh_dispatch_route_store_code');
            $table->string('wh_dispatch_route_code');
            $table->string('store_code');
            $table->integer('sort_order');
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();

            $table->primary(['wh_dispatch_route_store_code'],'pk_wdrs_wdrsc');
            $table->foreign('wh_dispatch_route_code','fk_wdrs_wdrc')->references('wh_dispatch_route_code')->on('warehouse_dispatch_routes');
            $table->foreign('store_code')->references('store_code')->on('stores_detail');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table wh_dispatch_route_stores add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wh_dispatch_route_stores');
    }
}
