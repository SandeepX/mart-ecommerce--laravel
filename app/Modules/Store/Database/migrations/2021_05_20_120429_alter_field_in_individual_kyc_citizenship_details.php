<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldInIndividualKycCitizenshipDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('individual_kyc_citizenship_details', function (Blueprint $table) {
            $table->string('citizenship_father_nationality')->nullable()->change();
            $table->string('citizenship_mother_nationality')->nullable()->change();

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
            //
        });
    }
}
