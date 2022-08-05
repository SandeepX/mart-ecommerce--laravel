<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewTableQuizSubmissionDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_submission_detail', function (Blueprint $table) {

            $table->string('qsd_code')->primary();
            $table->string('quiz_submission_code');
            $table->string('question_code');
            $table->mediumText('question');
            $table->string('correct_option');
            $table->string('answer');

            $table->timestamps();

            $table->foreign('question_code')->references('question_code')->on('quiz_game_questions');
            $table->foreign('quiz_submission_code')->references('quiz_submission_code')->on('quiz_submissions');

        });
        DB::statement('ALTER Table quiz_submission_detail add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_submission_detail');
    }
}
