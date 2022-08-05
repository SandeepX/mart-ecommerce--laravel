<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOtpVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $tableName = Schema::hasTable('otp_verifications');
       if($tableName){
           $currentTableName = 'otp_verifications';
           $newTableName = 'otp_verifications_old';
           Schema::rename($currentTableName, $newTableName);
       }

        Schema::create('otp_verifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('entity');
            $table->string('entity_code');
            $table->bigInteger('otp_code');
            $table->boolean('useable')->default(1);
            $table->enum('otp_request_via',['phone','email']);
            $table->timestamp('expires_at');
            $table->enum('purpose',['account_registration','phone_verification','email_verification']);
            $table->softDeletes();
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
        Schema::dropIfExists('otp_verifications');
    }
}
