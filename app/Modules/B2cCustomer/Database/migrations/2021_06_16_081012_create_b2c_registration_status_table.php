<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateB2cRegistrationStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2c_registration_status', function (Blueprint $table) {
            $table->string('b2c_registration_status_code');
            $table->string('user_code');
            $table->enum('status',['pending','processing','approved','rejected'])->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->primary(['b2c_registration_status_code'],'pk_smrs_ursc');
            $table->foreign('user_code')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table b2c_registration_status add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('b2c_registration_status');
    }
}
