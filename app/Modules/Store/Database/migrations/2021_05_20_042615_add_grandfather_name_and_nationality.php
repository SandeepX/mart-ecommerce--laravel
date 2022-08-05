<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGrandfatherNameAndNationality extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('individual_kyc_citizenship_details', function (Blueprint $table) {
           $table->string('citizenship_grandfather_name')->nullable()->after('citizenship_mother_nationality');
           $table->string('citizenship_grandfather_nationality')->nullable()->after('citizenship_grandfather_name');
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
