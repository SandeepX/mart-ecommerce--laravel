<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeliveryStatusToStorePreorderDetailsTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_preorder_details', function (Blueprint $table) {
            //
            $table->boolean('delivery_status')->default(1)->after('is_taxable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_preorder_details', function (Blueprint $table) {
            //
            $table->dropColumn('delivery_status');
        });
    }
}
