<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusTypeToWarehousePreorderListingsTbl extends Migration
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
            $table->enum('status_type',['processing','cancelled'])
                ->default('processing')->after('is_finalized');
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
            $table->dropColumn('status_type');
        });
    }
}
