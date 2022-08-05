<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWhDispatchRouteStoreOrdersTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wh_dispatch_route_store_orders', function (Blueprint $table) {

            $table->string('wh_dispatch_route_store_order_code');
            $table->string('wh_dispatch_route_store_code');
            $table->string('order_code');
            $table->enum('order_type',['bill_merge','normal_order','pre_order']);
            $table->double('total_amount');
            $table->string('created_by');
            $table->timestamps();

            $table->primary(['wh_dispatch_route_store_order_code'],'pk_wdrso_wdrsoc');
            $table->foreign('wh_dispatch_route_store_code','fk_wdrso_wdrsc')->references('wh_dispatch_route_store_code')->on('wh_dispatch_route_stores');
            $table->foreign('created_by')->references('user_code')->on('users');
        });

        DB::statement('ALTER Table wh_dispatch_route_store_orders add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wh_dispatch_route_store_orders');
    }
}
