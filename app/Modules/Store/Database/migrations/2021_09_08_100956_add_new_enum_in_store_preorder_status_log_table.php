<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewEnumInStorePreorderStatusLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_preorder_status_log', function (Blueprint $table) {
            DB::statement("ALTER TABLE store_preorder_status_log MODIFY status ENUM('pending','finalized','dispatched','cancelled','processing','ready_to_dispatch') NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_preorder_status_log', function (Blueprint $table) {
            DB::statement("ALTER TABLE store_preorder_status_log MODIFY status ENUM('pending','finalized','dispatched','cancelled','processing') NOT NULL");
        });
    }
}
