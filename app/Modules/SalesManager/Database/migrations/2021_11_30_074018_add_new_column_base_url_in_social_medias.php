<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnBaseUrlInSocialMedias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('social_medias', function (Blueprint $table) {
            $table->string('base_url')->after('social_media_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('social_medias', function (Blueprint $table) {
            $table->dropColumn('base_url');
        });
    }
}
