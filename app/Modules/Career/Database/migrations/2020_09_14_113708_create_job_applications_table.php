<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->string('application_code', 20)->unique()->primary();
            $table->string('job_opening_code',20);//foreign
            $table->string('tracking_code')->unique();
            $table->string('name');
            $table->string('email');
            $table->enum('gender',['m','f','other']);
            $table->string('phone_num');
            $table->json('other_contacts')->nullable();
            $table->string('temp_location_code');//foreign
            $table->string('temp_local_address');
            $table->string('perm_location_code');//foreign
            $table->string('perm_local_address');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('job_opening_code')->references('opening_code')->on('job_openings')->onDelete('no action');
            $table->foreign('temp_location_code')->references('location_code')->on('location_hierarchy')->onDelete('no action');
            $table->foreign('perm_location_code')->references('location_code')->on('location_hierarchy')->onDelete('no action');
            $table->foreign('deleted_by')->references('user_code')->on('users')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_applications');
    }
}
