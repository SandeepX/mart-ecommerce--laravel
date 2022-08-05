<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKycAgreementVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kyc_agreement_videos', function (Blueprint $table) {
            
            $table->string('kyc_agreement_vcode')->unique()->primary();
            $table->string('user_code');
            $table->string('store_code');

            $table->enum('agreement_video_for',['samjhauta_patra','akhtiyari_patra']);
            $table->string('agreement_video_name');

            $table->timestamps();

            $table->foreign('user_code')->references('user_code')->on('users');
            $table->foreign('store_code')->references('store_code')->on('stores_detail');
            
           
        });

        DB::statement('ALTER Table kyc_agreement_videos add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kyc_agreement_videos');
    }
}
