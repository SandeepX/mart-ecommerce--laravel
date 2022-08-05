<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCareerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('careers', function (Blueprint $table) {
            $table->string('career_code')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('descriptions');
            $table->boolean('is_active')->default(0);
            $table->string('created_by');
            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table careers add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('careers');
    }
}
