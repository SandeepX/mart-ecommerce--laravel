<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnInQuizParticipatorDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quiz_participator_detail', function (Blueprint $table) {
            $table->text('remarks')->nullable()->after('status_reponded_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quiz_participator_detail', function (Blueprint $table) {
            $table->dropColumn('remarks');
        });
    }
}
