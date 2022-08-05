<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAcceptenceStatusToStoreOrderDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_order_details', function (Blueprint $table) {
            $table->enum('acceptance_status',['pending','accepted','rejected'])->default('pending');
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
            $table->dropColumn('acceptence_status');
        });
    }
}
