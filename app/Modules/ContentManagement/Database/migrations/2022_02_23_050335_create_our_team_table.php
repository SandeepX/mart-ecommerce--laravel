<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateOurTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('our_teams', function (Blueprint $table) {
            $table->string('our_team_code')->unique()->primary();
            $table->string('name');
            $table->string('image');
            $table->string('department');
            $table->string('delegation');
            $table->longText('message')->nullable();
            $table->boolean('is_active');
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by');
            $table->softDeletes();
            $table->timestamps();
        });
        DB::statement('ALTER Table our_teams add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT First');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('our_teams');
    }
}
