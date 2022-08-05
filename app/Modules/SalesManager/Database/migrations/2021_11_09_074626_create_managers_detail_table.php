<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagersDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('managers_detail', function (Blueprint $table) {
            $table->string('manager_code')->primary();
            $table->string('manager_name');
            $table->string('manager_email')->unique();
            $table->string('manager_phone_no')->unique();
            $table->string('manager_photo')->nullable();
            $table->boolean('has_two_wheeler_license')->default(0);
            $table->boolean('has_four_wheeler_license')->default(0);
            $table->boolean('is_active')->default(0);
            $table->string('temporary_ward_code');
            $table->string('permanent_ward_code');
            $table->string('temporary_full_location');
            $table->string('permanent_full_location');
            $table->string('referral_code')->nullable();
            $table->enum('status',['pending','processing','approved','rejected'])->default('pending');
            $table->string('assigned_area_code')->nullable();
            $table->longText('remarks')->nullable();
            $table->string('user_code');
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('temporary_ward_code')->references('location_code')->on('location_hierarchy');
            $table->foreign('permanent_ward_code')->references('location_code')->on('location_hierarchy');
            $table->foreign('assigned_area_code')->references('location_code')->on('location_hierarchy');
            $table->foreign('user_code')->references('user_code')->on('users');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
        });

        DB::statement('ALTER Table managers_detail add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('managers_detail');
    }
}
