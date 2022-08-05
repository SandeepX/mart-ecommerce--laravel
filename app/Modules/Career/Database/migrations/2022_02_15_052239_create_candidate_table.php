<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->string('candidate_code')->primary();
            $table->string('career_id');
            $table->string('name');
            $table->string('email');
            $table->string('phone_number');
            $table->enum('gender',['male','female','others']);
            $table->text('cover_letter');
            $table->string('cv_file');
            $table->timestamps();
            $table->foreign('career_id')->references('career_code')->on('careers');

        });
        DB::statement('ALTER Table candidates add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST ');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidates');
    }
}
