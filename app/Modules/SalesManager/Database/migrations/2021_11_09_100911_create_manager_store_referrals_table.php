<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagerStoreReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager_store_referrals', function (Blueprint $table) {
            $table->string('manager_store_referrals_code')->primary();
            $table->string('manager_code');
            $table->string('referred_store_code');
            $table->double('referred_incentive_amount')->nullable();
            $table->json('referred_incentive_amount_meta')->nullable();
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();

            $table->foreign('manager_code')->references('manager_code')->on('managers_detail');
            $table->foreign('referred_store_code')->references('store_code')->on('stores_detail');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
        });

        DB::statement('ALTER Table manager_store_referrals add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager_store_referrals');
    }
}
