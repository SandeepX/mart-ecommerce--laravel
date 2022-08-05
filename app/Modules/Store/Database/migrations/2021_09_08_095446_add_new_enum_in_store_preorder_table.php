<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewEnumInStorePreorderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_preorder', function (Blueprint $table) {
            DB::statement("ALTER TABLE store_preorder MODIFY status ENUM('pending','finalized','dispatched','cancelled','processing','ready_to_dispatch') NOT NULL DEFAULT 'pending'");
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
            DB::statement("ALTER TABLE store_preorder MODIFY status ENUM('pending','finalized','dispatched','cancelled','processing') NOT NULL");
        });
    }
}
