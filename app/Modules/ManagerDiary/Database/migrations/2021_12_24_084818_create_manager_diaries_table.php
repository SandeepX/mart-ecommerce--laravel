<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagerDiariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager_diaries', function (Blueprint $table) {
            $table->string('manager_diary_code')->primary();
            $table->string('manager_code');
            $table->string('store_name');
            $table->string('referred_store_code')->nullable();
            $table->string('owner_name');
            $table->string('phone_no')->unique();
            $table->string('alt_phone_no')->nullable();
            $table->string('pan_no');
            $table->string('ward_code');
            $table->string('full_location')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->double('business_investment_amount');
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('manager_code')->references('manager_code')->on('managers_detail');
            $table->foreign('referred_store_code')->references('store_code')->on('stores_detail');
            $table->foreign('ward_code')->references('location_code')->on('location_hierarchy');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table manager_diaries add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager_diaries');
    }
}
