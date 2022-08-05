<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRemarksToWarehousePreorderListings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_preorder_listings', function (Blueprint $table) {
            //
            $table->longText('remarks')->nullable()->after('status_type');
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
            //
            $table->dropColumn('remarks');
        });
    }
}
