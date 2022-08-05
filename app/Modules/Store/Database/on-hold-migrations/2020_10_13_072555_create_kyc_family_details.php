<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKycFamilyDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individual_kyc_family_details', function (Blueprint $table) {
            $table->string('kyc_family_detail_code')->unique()->primary();
            $table->string('kyc_code');

            $table->string('spouse_name')->nullable();
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('grand_father_name');
            $table->string('grand_mother_name');

            $table->json('sons')->nullable();
            $table->json('daughters')->nullable();
            $table->json('daughter_in_laws')->nullable();

            $table->string('father_in_law')->nullable();
            $table->string('mother_in_law')->nullable();

            $table->timestamps();

            $table->foreign('kyc_code')->references('kyc_code')->on('individual_kyc_master');
        });
        DB::statement('ALTER Table individual_kyc_family_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('individual_kyc_family_details', function (Blueprint $table) {
            //
        });
    }
}
