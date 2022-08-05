<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobOpeningQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //pivot table
        Schema::create('job_opening_question', function (Blueprint $table) {
            $table->string('job_opening_code',20);
            $table->string('job_question_code',20);
            $table->integer('priority')->nullable();
            $table->timestamps();

            $table->unique(['job_opening_code','job_question_code']);

            $table->foreign('job_opening_code')->references('opening_code')->on('job_openings')->onDelete('no action');
            $table->foreign('job_question_code')->references('question_code')->on('job_questions')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_opening_question');
    }
}
