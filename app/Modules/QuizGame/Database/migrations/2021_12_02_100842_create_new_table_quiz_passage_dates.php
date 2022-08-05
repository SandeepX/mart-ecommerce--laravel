<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewTableQuizPassageDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_passage_dates', function (Blueprint $table) {
            $table->string('qpd_code')->primary();
            $table->string('qp_code');
            $table->date('quiz_passage_date');

            $table->timestamps();

            $table->foreign('qp_code')->references('qp_code')->on('quiz_game_passages');
        });
        DB::statement('ALTER Table quiz_passage_dates add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_passsages_dates');
    }
}
