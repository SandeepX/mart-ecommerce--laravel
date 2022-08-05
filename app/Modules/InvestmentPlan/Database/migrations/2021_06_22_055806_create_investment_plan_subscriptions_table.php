<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvestmentPlanSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investment_plan_subscriptions', function (Blueprint $table) {
            $table->string('ip_subscription_code')->unique()->primary();
            $table->string('investment_plan_code');

            $table->string('investment_plan_holder');
            $table->string('investment_holder_type');
            $table->string('investment_holder_id');

            $table->string('investment_plan_name');
            $table->integer('maturity_period');
            $table->string('ipir_option_code');
            $table->double('interest_rate');
            $table->double('price_start_range',20, 10);
            $table->double('price_end_range',20,10);

            $table->string('referred_by')->nullable();
            $table->boolean('is_mature')->default(0);
            $table->date('maturity_date');

            $table->boolean('is_active')->default(1);
            $table->string('created_by');
            $table->string('updated_by');

            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('referred_by')->references('user_code')->on('users');
            $table->foreign('investment_plan_code','ip_ips_foreign')->references('investment_plan_code')->on('investment_plans');
            $table->foreign('ipir_option_code','ipir_ips_foreign')->references('ipir_option_code')->on('investment_plan_interest_release_options');

        });

        DB::statement('ALTER Table investment_plan_subscriptions add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('investment_plan_subscriptions');
    }
}
