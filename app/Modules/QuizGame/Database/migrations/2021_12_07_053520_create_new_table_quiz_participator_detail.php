<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewTableQuizParticipatorDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_participator_detail', function (Blueprint $table) {

            $table->string('qpd_code')->primary();
            $table->string('participator_type');
            $table->string('participator_code');
            $table->string('store_name');
            $table->double('store_pan_no');
            $table->string('store_location_ward_code');
            $table->string('store_full_location');
            $table->double('recharge_phone_no');
            $table->enum('status',['pending','approved','rejected'])->default('pending');
            $table->date('status_reponded_at')->nullable();

            $table->string('created_by');
            $table->string('updated_by');

            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');

        });
        DB::statement('ALTER Table quiz_participator_detail add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_participator_detail');
    }
}
