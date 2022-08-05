<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKycCitizenshipDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individual_kyc_citizenship_details', function (Blueprint $table) {
            $table->string('kyc_c_d_code')->unique()->primary();
            $table->string('kyc_code');

            $table->string('citizenship_no');
            $table->string('citizenship_full_name');
            $table->string('citizenship_nationality');
            $table->string('citizenship_issued_date');
            $table->enum('citizenship_gender',['m','f','others']);

            $table->string('citizenship_birth_place');
            $table->string('citizenship_district');
            $table->string('citizenship_municipality');
            $table->string('citizenship_ward_no');

            $table->string('citizenship_dob');

            $table->string('citizenship_father_name');
            $table->string('citizenship_father_address');
            $table->string('citizenship_father_nationality');

            $table->string('citizenship_mother_name');
            $table->string('citizenship_mother_address');
            $table->string('citizenship_mother_nationality');

            $table->string('citizenship_spouse_name')->nullable();
            $table->string('citizenship_spouse_address')->nullable();
            $table->string('citizenship_spouse_nationality')->nullable();

            $table->timestamps();


            $table->foreign('kyc_code')->references('kyc_code')->on('individual_kyc_master');

        });
        DB::statement('ALTER Table individual_kyc_citizenship_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('individual_kyc_citizenship_details', function (Blueprint $table) {
            //
        });
    }
}
