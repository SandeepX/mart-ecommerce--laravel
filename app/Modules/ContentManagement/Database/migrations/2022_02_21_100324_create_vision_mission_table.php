<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateVisionMissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visions', function (Blueprint $table) {
            $table->string('vision_code')->unique()->primary();
            $table->string('page_image');
            $table->longText('vision_description');
            $table->longText('mission_description');
            $table->boolean('is_active')->default(0);
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();
        });
        DB::statement('ALTER Table visions add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT First');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visions');
    }
}
