<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_orders', function (Blueprint $table) {
            $table->string('warehouse_order_code')->unique()->primary();
            $table->string('vendor_code');
            $table->string('warehouse_code');
            $table->text('order_note')->nullable();
            $table->date('order_date')->nullable();
            $table->enum('status',['draft','sent','processing','delivering','received']);
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();

            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');

            $table->foreign('vendor_code')->references('vendor_code')->on('vendors_detail');
            $table->foreign('warehouse_code')->references('warehouse_code')->on('warehouses');

        });
        DB::statement('ALTER Table warehouse_orders add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_orders');
    }
}
