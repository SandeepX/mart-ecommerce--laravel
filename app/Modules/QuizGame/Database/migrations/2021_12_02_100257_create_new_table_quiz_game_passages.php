<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewTableQuizGamePassages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_game_passages', function (Blueprint $table) {

            $table->string('qp_code')->primary();
            $table->string('passage_title');
            $table->longText('passage');
            $table->boolean('passage_is_active')->default(1);
            $table->bigInteger('total_passage_points')->nullable();
            $table->string('created_by');
            $table->string('updated_by');

            $table->timestamps();
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');

        });
        DB::statement('ALTER Table quiz_game_passages add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_game_passages');
    }
}
