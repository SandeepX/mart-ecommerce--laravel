<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseOrderPlacementByAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_order_placement_by_admin', function (Blueprint $table) {
            $table->string('order_code')->unique()->primary();
            $table->date('order_date');
            $table->string('warehouse_code');
            $table->string('vendor_code');
            $table->string('user_code');
            $table->enum('sent_status',['draft','sent']);
            $table->date('sent_date')->nullable();
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
            $table->foreign('warehouse_code')->references('warehouse_code')->on('warehouses');
            $table->foreign('vendor_code')->references('vendor_code')->on('vendors_detail');
            $table->foreign('user_code')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table warehouse_order_placement_by_admin add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_order_placement_by_admin');
    }
}
