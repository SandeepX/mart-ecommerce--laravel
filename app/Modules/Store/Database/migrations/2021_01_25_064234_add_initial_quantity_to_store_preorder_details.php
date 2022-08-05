<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInitialQuantityToStorePreorderDetails extends Migration
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
            $table->integer('initial_order_quantity')->after('quantity');
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
            $table->dropColumn('initial_order_quantity');
        });
    }
}
