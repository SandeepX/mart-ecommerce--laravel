<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvestmentPlanInterestReleaseOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investment_plan_interest_release_options', function (Blueprint $table) {
            $table->string('ipir_option_code')->unique();

            $table->string('investment_plan_code');
            $table->enum('interest_release_time',['monthly','yearly','quaterly','semi-annually'])->nullable();
            $table->boolean('is_active')->default(1);
            $table->string('created_by');
            $table->string('updated_by');

            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('investment_plan_code','ipc_ipiroc_foreign')->references('investment_plan_code')->on('investment_plans');

        });

        DB::statement('ALTER Table investment_plan_interest_release_options add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('investment_plan_interest_release_options');
    }
}
