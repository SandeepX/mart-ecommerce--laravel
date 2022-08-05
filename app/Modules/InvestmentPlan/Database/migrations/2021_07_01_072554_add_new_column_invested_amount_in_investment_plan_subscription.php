<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnInvestedAmountInInvestmentPlanSubscription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investment_plan_subscriptions', function (Blueprint $table) {
            $table->double('invested_amount',20,10)->after('interest_rate');
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
            //
        });
    }
}
