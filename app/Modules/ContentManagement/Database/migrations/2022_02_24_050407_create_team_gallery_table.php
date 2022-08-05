<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamGalleryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_galleries', function (Blueprint $table) {
            $table->string('team_gallery_code')->unique()->primary();
            $table->string('image');
            $table->text('description')->nullable();
            $table->boolean('is_active');
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by');
            $table->softDeletes();
            $table->timestamps();
        });
        DB::statement('ALTER Table team_galleries add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT First');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_galleries');
    }
}
