<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFirmKycBankDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firm_kyc_bank_details', function (Blueprint $table) {
            $table->string('kyc_bank_detail_code')->unique()->primary();
            $table->string('kyc_code');

            $table->string('bank_code');
            $table->string('bank_branch_name');
            $table->string('bank_account_no');
            $table->string('bank_account_holder_name');
            

            $table->timestamps();

            $table->foreign('kyc_code')->references('kyc_code')->on('firm_kyc_master');
            $table->foreign('bank_code')->references('bank_code')->on('banks');

        });
        DB::statement('ALTER Table firm_kyc_bank_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('firm_kyc_bank_details');
    }
}
