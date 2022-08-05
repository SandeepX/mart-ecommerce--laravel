<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewTableQuizGameQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_game_questions', function (Blueprint $table) {

            $table->string('question_code')->primary();
            $table->string('qp_code');
            $table->text('question');
            $table->string('option_a');
            $table->string('option_b');
            $table->string('option_c');
            $table->string('option_d');
            $table->string('correct_answer');
            $table->double('points');
            $table->boolean('question_is_active')->default(1);

            $table->string('created_by');
            $table->string('updated_by');

            $table->timestamps();

            $table->foreign('qp_code')->references('qp_code')->on('quiz_game_passages');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');

        });
        DB::statement('ALTER Table quiz_game_questions add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_game_questions');
    }
}
