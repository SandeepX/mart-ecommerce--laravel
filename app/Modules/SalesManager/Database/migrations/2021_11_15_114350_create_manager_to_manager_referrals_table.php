<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateManagerToManagerReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager_to_manager_referrals', function (Blueprint $table) {
            $table->string('manager_to_manager_referrals_code');
            $table->string('manager_code');
            $table->string('referred_manager_code');
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();

            $table->primary('manager_to_manager_referrals_code','pk_mtmr_mtmrc');
            $table->foreign('manager_code')->references('manager_code')->on('managers_detail');
            $table->foreign('referred_manager_code')->references('manager_code')->on('managers_detail');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table manager_to_manager_referrals add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager_to_manager_referrals');
    }
}
