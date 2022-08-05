<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewEnumInBillMergeMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bill_merge_master', function (Blueprint $table) {
            DB::statement("ALTER TABLE bill_merge_master MODIFY status ENUM('pending','dispatched','cancelled','ready_to_dispatch') NOT NULL DEFAULT 'pending'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bill_merge_master', function (Blueprint $table) {
            DB::statement("ALTER TABLE bill_merge_master MODIFY status ENUM('pending','dispatched','cancelled') NOT NULL DEFAULT 'pending'");
        });
    }
}
