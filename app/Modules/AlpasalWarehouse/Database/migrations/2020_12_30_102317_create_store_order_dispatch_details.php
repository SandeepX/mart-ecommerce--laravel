<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;



class CreateStoreOrderDispatchDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_order_dispatch_details', function (Blueprint $table) {
            $table->string('store_order_dispatch_detail_code')->unique('uq_soddc');
            $table->primary(['store_order_dispatch_detail_code'],'soddc_primary');
            $table->string('store_order_code');
            $table->string('vehicle_name',100);
            $table->string('vehicle_type',100);
            $table->string('vehicle_number',100);
            $table->timestamp('expected_delivery_time');
            $table->string('created_by');


            $table->foreign('store_order_code')->references('store_order_code')->on('store_orders');
            $table->foreign('created_by')->references('user_code')->on('users');

            $table->timestamps();
        });

        DB::statement('ALTER Table store_order_dispatch_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_order_dispatch_details');
    }
}
