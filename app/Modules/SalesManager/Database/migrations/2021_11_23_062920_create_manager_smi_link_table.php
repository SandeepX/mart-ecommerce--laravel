<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagerSmiLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('manager_smi_links', function (Blueprint $table) {
            $table->string('msmi_link_code')->primary();
            $table->string('msmi_code');
            $table->string('sm_code');
            $table->json('social_media_links');

            $table->timestamps();
            $table->string('created_by');
            $table->string('updated_by');

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('msmi_code')->references('msmi_code')->on('manager_smi');
            $table->foreign('sm_code')->references('sm_code')->on('social_medias');


        });
        DB::statement('ALTER Table manager_smi_links add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager_smi_links');
    }
}
