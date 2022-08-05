<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToWarehouseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_orders', function (Blueprint $table) {
            //
            $table->double('total_amount')->after('warehouse_code');
           // $table->double('taxable_amount')->after('total_amount')->default(0);
            $table->double('accepted_amount')->after('total_amount')->nullable();
            //$table->double('accepted_tax_amount')->after('accepted_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_orders', function (Blueprint $table) {
            //
        });
    }
}
