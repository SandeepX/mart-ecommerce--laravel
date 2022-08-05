<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewStatusReadyToDispatchInStoreOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_orders', function (Blueprint $table) {
            DB::statement("ALTER TABLE store_orders MODIFY delivery_status ENUM('pending','dispatched','processing','accepted','received','cancelled','partially-accepted','under-verification','ready_to_dispatch') NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_orders', function (Blueprint $table) {
            DB::statement("ALTER TABLE store_orders MODIFY delivery_status ENUM('pending','dispatched','processing','accepted','received','cancelled','partially-accepted','under-verification') NOT NULL");
        });
    }
}
