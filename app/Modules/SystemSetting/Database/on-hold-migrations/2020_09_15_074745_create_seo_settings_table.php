<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeoSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('meta_title');
            $table->longText('meta_description');
            $table->json('keywords');
            $table->integer('revisit_after');
            $table->string('author');
            $table->string('sitemap_link');
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
        Schema::dropIfExists('seo_settings');
    }
}
