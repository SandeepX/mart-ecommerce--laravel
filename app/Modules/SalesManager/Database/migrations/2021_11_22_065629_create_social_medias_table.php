<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('social_medias', function (Blueprint $table) {
            $table->string('sm_code')->primary();
            $table->string('social_media_name');
            $table->boolean('enabled_for_smi')->default(0);

            $table->timestamps();
            $table->string('created_by');
            $table->string('updated_by');
            $table->softDeletes();
            $table->string('deleted_by')->nullable();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');

        });
        DB::statement('ALTER Table social_medias add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_medias');
    }
}
