<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToStorePreorderTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_preorder', function (Blueprint $table) {
            //
            $table->enum('status',['pending','finalized','dispatched','cancelled'])->default('pending')->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_preorder', function (Blueprint $table) {
            //
            $table->dropColumn('status');
        });
    }
}
