<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToWarehouseOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_order_details', function (Blueprint $table) {
            //
            $table->enum('acceptance_status',['pending','accepted','rejected'])
                ->after('retail_margin_value')->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_order_details', function (Blueprint $table) {
            //
        });
    }
}
