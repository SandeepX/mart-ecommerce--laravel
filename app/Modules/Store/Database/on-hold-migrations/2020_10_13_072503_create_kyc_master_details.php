<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKycMasterDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individual_kyc_master', function (Blueprint $table) {
           
            $table->string('kyc_code')->unique()->primary();
            $table->string('user_code');
            $table->string('store_code');
            $table->enum('kyc_for',['sanchalak','akhtiyari']);
            $table->string('name_in_devanagari');
            $table->string('name_in_english');
            $table->enum('marital_status',['married','unmarried']);
            $table->enum('gender',['m','f','others']);
            $table->string('pan_no');
            $table->enum('educational_qualification',['illiterate','literate','see','plus_two','bachelors','masters','phd']);
            
            $table->string('permanent_house_no')->nullable();
            $table->string('permanent_street');
//            $table->string('permanent_province');
//            $table->string('permanent_district');
//            $table->string('permanent_municipality');
            $table->string('permanent_ward_no');

            $table->string('present_house_no')->nullable();
            $table->string('present_street');
//            $table->string('present_province');
//            $table->string('present_district');
//            $table->string('present_municipality');
            $table->string('present_ward_no');

            $table->string('landmark'); // residing at
            $table->double('latitude');
            $table->double('longitude');


            $table->string('landlord_name')->nullable();
            $table->string('landlord_contact_no')->nullable();
            
            /*$table->boolean('is_verified')->default(0);
            $table->string('verified_by')->nullable();
            $table->string('verified_at')->nullable();*/

            $table->enum('verification_status',['pending','verified','rejected'])->default('pending');
            $table->string('responded_by')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->longText('remarks')->nullable();
            $table->timestamps();


            $table->foreign('user_code')->references('user_code')->on('users');
            $table->foreign('store_code')->references('store_code')->on('stores_detail');
            $table->foreign('responded_by')->references('user_code')->on('users');

//            $table->foreign('permanent_province')->references('location_code')->on('location_hierarchy');
//            $table->foreign('permanent_district')->references('location_code')->on('location_hierarchy');
//            $table->foreign('permanent_municipality')->references('location_code')->on('location_hierarchy');
            $table->foreign('permanent_ward_no')->references('location_code')->on('location_hierarchy');


//            $table->foreign('present_province')->references('location_code')->on('location_hierarchy');
//            $table->foreign('present_district')->references('location_code')->on('location_hierarchy');
//            $table->foreign('present_municipality')->references('location_code')->on('location_hierarchy');
            $table->foreign('present_ward_no')->references('location_code')->on('location_hierarchy');
           
            
        });

        

        DB::statement('ALTER Table individual_kyc_master add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('individual_kyc_master', function (Blueprint $table) {
            //
        });
    }
}
