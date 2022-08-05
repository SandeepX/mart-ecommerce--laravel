<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillMergeMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_merge_master', function (Blueprint $table) {
            $table->string('bill_merge_master_code')->primary();
            $table->string('warehouse_code');
            $table->string('store_code');
            $table->string('remarks')->nullable();
            $table->enum('status', ['pending','dispatched','cancelled'])->default('pending');
            $table->string('created_by');
            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('store_code')->references('store_code')->on('stores_detail');
            $table->foreign('warehouse_code')->references('warehouse_code')->on('warehouses');
        });
        DB::statement('ALTER Table bill_merge_master add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_merge_master');
    }
}
