<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobileAppDeploymentVersionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_app_deployment_version', function (Blueprint $table) {
            $table->increments('id');
            $table->string('manager_version')->nullable();
            $table->string('manager_build_number')->nullable();
            $table->string('store_version')->nullable();
            $table->string('store_build_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobile_app_deployment_version');
    }
}
