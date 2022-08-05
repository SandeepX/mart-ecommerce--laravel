<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVerifiedToFirmKycMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('firm_kyc_master', function (Blueprint $table) {
            //

            $table->enum('verification_status',['pending','verified','rejected'])->default('pending')->after('share_holders_no');
            $table->string('responded_by')->nullable()->after('verification_status');
            $table->timestamp('responded_at')->nullable()->after('responded_by');
            $table->longText('remarks')->nullable()->after('responded_at');

            $table->foreign('responded_by')->references('user_code')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('firm_kyc_master', function (Blueprint $table) {
            //
        });
    }
}
