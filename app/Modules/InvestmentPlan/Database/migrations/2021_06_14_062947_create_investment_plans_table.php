<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvestmentPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investment_plans', function (Blueprint $table) {

            $table->string('investment_plan_code')->unique()->primary();
            $table->string('name');
            $table->integer('maturity_period');
            $table->double('target_capital',20,10);
            $table->double('price_start_range',20, 10);
            $table->double('price_end_range',20,10);
            $table->boolean('is_active')->default(1);
            $table->string('created_by');
            $table->string('updated_by');

            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');

        });

        DB::statement('ALTER Table investment_plans add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('investment_plans');
    }
}
