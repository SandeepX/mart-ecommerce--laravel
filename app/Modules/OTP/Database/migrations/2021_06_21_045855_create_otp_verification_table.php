<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOtpVerificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otp_verifications', function (Blueprint $table) {
            $table->string('otp_verification_code');

            $table->primary(['otp_verification_code'],'otp_primary');

            $table->string('user_code');
            $table->bigInteger('otp_code')->unique();
            $table->enum('otp_for',['registration'])->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_claimed')->default(0);
            $table->string('created_by');
            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('user_code')->references('user_code')->on('users');

        });

        DB::statement('ALTER Table otp_verifications add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('otp_verifications');
    }
}
