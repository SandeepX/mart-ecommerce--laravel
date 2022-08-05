<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnContactNumberInStoreOrderDispatchDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_order_dispatch_details', function (Blueprint $table) {
            $table->string('contact_number')->after('vehicle_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_order_dispatch_details', function (Blueprint $table) {
            $table->dropColumn('contact_number');
        });
    }
}
