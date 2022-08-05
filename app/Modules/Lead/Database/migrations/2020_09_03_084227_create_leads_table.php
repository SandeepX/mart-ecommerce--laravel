<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadsTable extends Migration
{
    public function up()
    {
        Schema::create('leads_detail', function (Blueprint $table) {
            $table->string('lead_code')->unique()->primary();
            $table->string('lead_name');
            // $table->string('slug')->unique()->index();
            $table->string('lead_location_code');
            $table->string('lead_landmark')->nullable();
            $table->double('landmark_latitude')->nullable();
            $table->double('landmark_longitude')->nullable();
            $table->string('lead_phone_no')->nullable();
            $table->string('lead_alternative_phone_no')->nullable();
            $table->string('lead_email')->nullable();
            $table->string('remarks')->nullable();
            // $table->string('user_code');
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // $table->foreign('user_code')->references('user_code')->on('users');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
          
        });
        DB::statement('ALTER Table leads_detail add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads_detail');
    }
}
