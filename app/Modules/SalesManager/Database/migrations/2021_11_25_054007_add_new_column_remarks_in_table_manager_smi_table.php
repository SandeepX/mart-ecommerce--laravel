<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnRemarksInTableManagerSmiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manager_smi', function (Blueprint $table) {
            $table->text('remarks')->nullable()->after('edit_allowed_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manager_smi', function (Blueprint $table) {
            $table->dropColumn('remarks');
        });
    }
}
