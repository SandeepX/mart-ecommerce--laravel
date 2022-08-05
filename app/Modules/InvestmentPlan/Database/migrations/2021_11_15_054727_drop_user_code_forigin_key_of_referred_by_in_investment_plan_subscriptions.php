<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropUserCodeForiginKeyOfReferredByInInvestmentPlanSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investment_plan_subscriptions', function (Blueprint $table) {
            $table->dropForeign('investment_plan_subscriptions_referred_by_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('investment_plan_subscriptions', function (Blueprint $table) {
            $table->foreign('referred_by')->references('user_code')->on('users');
        });
    }
}
