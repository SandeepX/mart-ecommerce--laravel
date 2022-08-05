<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnAllowEditRemarksInManagerSmi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manager_smi', function (Blueprint $table) {
            $table->text('allow_edit_remarks')->nullable()->after('remarks');
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
            $table->dropColumn('allow_edit_remarks');
        });
    }
}
