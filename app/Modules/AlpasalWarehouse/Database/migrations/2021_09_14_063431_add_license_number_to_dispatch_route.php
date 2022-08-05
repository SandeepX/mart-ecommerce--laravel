<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLicenseNumberToDispatchRoute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_dispatch_routes', function (Blueprint $table) {
            //
            $table->string('driver_license_number')->nullable()->after('driver_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_dispatch_routes', function (Blueprint $table) {
            //
            $table->dropColumn(['driver_license_number']);
        });
    }
}
