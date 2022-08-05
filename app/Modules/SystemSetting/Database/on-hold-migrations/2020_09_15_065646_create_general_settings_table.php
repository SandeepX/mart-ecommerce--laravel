<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('logo');
            $table->longText('favicon');
            $table->longText('admin_sidebar_logo');
            $table->string('full_address');
            $table->string('primary_contact');
            $table->string('secondary_contact');
            $table->text('facebook');
            $table->text('twitter');
            $table->text('instagram');
            $table->boolean('is_maintenance_mode');
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
        Schema::dropIfExists('general_settings');
    }
}
