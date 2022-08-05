<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsActiveFieldToVendorsDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendors_detail', function (Blueprint $table) {
            $table->boolean('is_active')->default(1)->after('vendor_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendors_detail', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
}
