<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationHierarchiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_hierarchy', function (Blueprint $table) {
            $table->string('location_code')->unique()->primary();
            $table->string('location_name');
           // $table->string('slug');
            $table->string('location_name_devanagari')->nullable();
            $table->string('upper_location_code')->nullable();
            $table->enum('location_type',['country', 'province', 'district', 'municipality', 'ward', 'tole/street']);
            $table->string('headquarter')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
        });

        Schema::table('location_hierarchy', function (Blueprint $table) {
            $table->foreign('upper_location_code')->references('location_code')->on('location_hierarchy');
        });

        DB::statement('ALTER Table location_hierarchy add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
