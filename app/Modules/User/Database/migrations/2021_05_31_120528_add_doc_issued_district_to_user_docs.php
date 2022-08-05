<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDocIssuedDistrictToUserDocs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_docs', function (Blueprint $table) {
            $table->string('doc_issued_district')->nullable();
            $table->foreign('doc_issued_district')->references('location_code')->on('location_hierarchy');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_docs', function (Blueprint $table) {
            //
        });
    }
}
