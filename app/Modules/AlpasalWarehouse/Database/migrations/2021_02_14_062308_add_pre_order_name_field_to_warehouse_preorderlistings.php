<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPreOrderNameFieldToWarehousePreorderlistings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_preorder_listings', function (Blueprint $table) {
            $table->string('pre_order_name')->after('warehouse_preorder_listing_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_preorder_listings', function (Blueprint $table) {
            $table->dropColumn('pre_order_name');
        });
    }
}
