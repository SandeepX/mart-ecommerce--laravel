<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnvPassportSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('env_passport_settings', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('passport_login_endpoint');
            $table->integer('passport_client_id');
            $table->string('passport_client_secret');
            $table->string('updated_by');
            $table->timestamps();

            $table->foreign('updated_by')->references('user_code')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('env_passport_settings');
    }
}
