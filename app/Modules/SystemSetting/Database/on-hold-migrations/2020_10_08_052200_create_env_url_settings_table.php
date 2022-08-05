<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnvUrlSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('env_url_settings', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('ecommerce_site_url');
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
        Schema::dropIfExists('env_url_settings');
    }
}
