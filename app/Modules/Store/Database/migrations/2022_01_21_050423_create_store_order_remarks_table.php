<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreOrderRemarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_order_remarks', function (Blueprint $table) {
            $table->string('store_order_remark_code');
            $table->string('store_order_code');
            $table->longText('remark');
            $table->string('created_by');
            $table->timestamps();

            $table->primary('store_order_remark_code','pk_sor_sorc');
            $table->foreign('store_order_code')->references('store_order_code')->on('store_orders');
            $table->foreign('created_by')->references('user_code')->on('users');

        });
        DB::statement('ALTER Table store_order_remarks add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_order_remarks');
    }
}
