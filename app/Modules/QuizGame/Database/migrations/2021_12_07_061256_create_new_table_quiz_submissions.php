<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewTableQuizSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('quiz_submissions', function (Blueprint $table) {

            $table->string('quiz_submission_code')->primary();
            $table->string('qp_code');
            $table->string('participator_type');
            $table->string('participator_code');
            $table->date('submitted_date');
            $table->string('submitted_by');
            $table->timestamps();

            $table->foreign('submitted_by')->references('user_code')->on('users');
            $table->foreign('qp_code')->references('qp_code')->on('quiz_game_passages');

        });
        DB::statement('ALTER Table quiz_submissions add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_submissions');
    }
}
