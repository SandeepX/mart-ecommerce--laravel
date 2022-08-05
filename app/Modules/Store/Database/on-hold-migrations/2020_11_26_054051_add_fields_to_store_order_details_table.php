<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToStoreOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_order_details', function (Blueprint $table) {
            //$table->boolean('is_accepted')->default(0)->after('unit_rate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_order_details', function (Blueprint $table) {
         //   $table->dropColumn('is_accepted');
        });
    }
}
