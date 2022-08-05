<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOtpAccountVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otp_account_verifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('entity');
            $table->bigInteger('otp_code');
            $table->enum('otp_request_source',['phone','email']);
            $table->string('otp_source_value');
            $table->timestamp('expires_at');
            $table->boolean('useable')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('otp_account_verifications');
    }
}
