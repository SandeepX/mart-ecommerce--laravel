<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCanUpdateKycFieldAndUpdatedRequestAllowedByFieldToKycMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('individual_kyc_master', function (Blueprint $table) {
            $table->boolean('can_update_kyc')->default(1)->after('responded_at');
            $table->string('update_request_allowed_by')->nullable();
            $table->timestamp('update_request_allowed_at')->nullable();
//            $table->foreign('update_request_allowed_by')->references('user_code')->on('users');
        });
        Schema::table('firm_kyc_master', function (Blueprint $table) {
            $table->boolean('can_update_kyc')->default(1)->after('responded_at');
            $table->string('update_request_allowed_by')->nullable();
            $table->timestamp('update_request_allowed_at')->nullable();
//            $table->foreign('update_request_allowed_by')->references('user_code')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('individual_kyc_master', function (Blueprint $table) {
            $table->dropColumn('can_update_kyc');
            $table->dropColumn('update_request_allowed_by');
        });
        Schema::table('firm_kyc_master', function (Blueprint $table) {
            $table->dropColumn('can_update_kyc');
            $table->dropColumn('update_request_allowed_by');
        });
    }
}
