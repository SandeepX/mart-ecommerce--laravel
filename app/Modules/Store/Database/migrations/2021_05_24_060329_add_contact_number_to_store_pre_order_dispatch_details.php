<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactNumberToStorePreOrderDispatchDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_pre_order_dispatch_details', function (Blueprint $table) {
            $table->string('contact_number')->nullable()->after('vehicle_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_pre_order_dispatch_details', function (Blueprint $table) {
            $table->dropColumn('contact_number');
        });
    }
}
