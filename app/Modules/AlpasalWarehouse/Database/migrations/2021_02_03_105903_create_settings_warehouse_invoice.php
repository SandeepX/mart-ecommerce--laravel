<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsWarehouseInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings_warehouse_invoice', function (Blueprint $table) {

            $table->string('setting_warehouse_invoice_code')->unique();
            $table->enum('order_type',['store_order','store_pre_order']);
            $table->string('warehouse_code');
            $table->integer('starting_number');
            $table->integer('ending_number');
            $table->string('fiscal_year_code');
            $table->integer('next_number');
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->primary(['setting_warehouse_invoice_code'],'pk_swi_swic');
            $table->foreign('warehouse_code')->references('warehouse_code')->on('warehouses');
            $table->foreign('fiscal_year_code')->references('fiscal_year_code')->on('fiscal_years');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
        });

        DB::statement('ALTER Table settings_warehouse_invoice add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings_warehouse_invoice');
    }
}
