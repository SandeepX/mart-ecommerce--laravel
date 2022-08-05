<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterIndividualKycCitizenshipDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('individual_kyc_citizenship_details', function (Blueprint $table) {
            $table->string('citizenship_full_name')->nullable()->change();
            $table->string('citizenship_municipality')->nullable()->change();
            $table->string('citizenship_ward_no')->nullable()->change();
            $table->string('citizenship_father_address')->nullable()->change();
            $table->string('citizenship_mother_address')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('individual_kyc_citizenship_details', function (Blueprint $table) {
            $table->string('citizenship_full_name')->nullable(false)->change();
            $table->string('citizenship_municipality')->nullable(false)->change();
            $table->string('citizenship_ward_no')->nullable(false)->change();
            $table->string('citizenship_father_address')->nullable(false)->change();
            $table->string('citizenship_mother_address')->nullable(false)->change();
        });
    }
}
