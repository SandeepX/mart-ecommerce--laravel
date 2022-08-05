<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseMinOrderAmountSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_min_order_amount_settings', function (Blueprint $table) {
            $table->string('warehouse_min_order_amount_setting_code');
            $table->string('warehouse_code');
            $table->integer('min_order_amount');
            $table->enum('status',[1,0])->default(1);
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();

            $table->primary(['warehouse_min_order_amount_setting_code'], 'pk_wmoasc');
            $table->foreign('warehouse_code')->references('warehouse_code')->on('warehouses');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');

        });

        DB::statement('ALTER Table warehouse_min_order_amount_settings add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_min_order_amount_settings');
    }
}
