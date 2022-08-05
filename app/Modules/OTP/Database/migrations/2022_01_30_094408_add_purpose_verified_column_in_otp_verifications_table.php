<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPurposeVerifiedColumnInOtpVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('otp_verifications', function (Blueprint $table) {
            $table->boolean('purpose_verified')->default(0)->after('purpose');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('otp_verifications', function (Blueprint $table) {
            $table->dropColumn(['purpose_verified']);
        });
    }
}
