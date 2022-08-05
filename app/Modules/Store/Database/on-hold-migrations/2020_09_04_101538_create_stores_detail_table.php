<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores_detail', function (Blueprint $table) {
            $table->string('store_code')->unique()->primary();

            $table->string('store_name');
            $table->string('slug')->unique()->index();
            $table->string('store_location_code');
            $table->string('store_owner');
            $table->string('store_size_code');
            $table->string('store_contact_phone');
            $table->string('store_contact_mobile');
            $table->string('store_email');
            $table->string('store_registration_type_code');
            $table->string('store_company_type_code');
            $table->date('store_established_date');
            $table->string('store_logo');
            $table->string('store_landmark_name');

            $table->double('latitude');
            $table->double('longitude');
            $table->string('user_code');
            $table->string('referred_by');
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
            $table->foreign('referred_by')->references('user_code')->on('users');
            $table->foreign('user_code')->references('user_code')->on('users');
            $table->foreign('store_location_code')->references('location_code')->on('location_hierarchy');
            $table->foreign('store_registration_type_code')->references('registration_type_code')->on('registration_types');
            $table->foreign('store_company_type_code')->references('company_type_code')->on('company_types');

        });
        DB::statement('ALTER Table store_documents add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores_detail');
    }
}
