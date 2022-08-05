<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors_detail', function (Blueprint $table) {
            $table->string('vendor_code')->unique()->primary();
            $table->string('vendor_name');
            $table->string('slug')->unique()->index();
            $table->string('vendor_type_code');
            $table->string('registration_type_code');
            $table->string('company_type_code');
            $table->string('company_size')->nullable();
            $table->string('vendor_location_code');
            $table->string('vendor_landmark')->nullable();
            $table->double('landmark_latitude')->nullable();
            $table->double('landmark_longitude')->nullable();
            $table->string('vendor_owner');
            $table->string('pan')->nullable();
            $table->string('vat')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_landline')->nullable();
            $table->string('contact_mobile')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_fax')->nullable();
            $table->string('user_code');
            $table->string('vendor_logo');
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_code')->references('user_code')->on('users');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
            $table->foreign('vendor_type_code')->references('vendor_type_code')->on('vendor_types');
            $table->foreign('vendor_location_code')->references('location_code')->on('location_hierarchy');
            $table->foreign('registration_type_code')->references('registration_type_code')->on('registration_types');
            $table->foreign('company_type_code')->references('company_type_code')->on('company_types');
        });
        DB::statement('ALTER Table vendors_detail add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors_detail');
    }
}
