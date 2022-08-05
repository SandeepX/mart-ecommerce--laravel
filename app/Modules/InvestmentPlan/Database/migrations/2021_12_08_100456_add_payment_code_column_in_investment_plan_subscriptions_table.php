<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentCodeColumnInInvestmentPlanSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investment_plan_subscriptions', function (Blueprint $table) {
            $table->string('payment_code')->nullable()->after('payment_mode');
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
            $table->dropColumn('payment_code');
        });
    }
}
