<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvestmentPlanCommissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investment_plan_commission', function (Blueprint $table) {
            $table->string('ipc_code')->unique()->primary();
            $table->string('investment_plan_code');
            $table->enum('commission_type',['annual','instant']);
            $table->enum('commission_mount_type',['p','f']);
            $table->double('commission_amount_value',20, 10);
            $table->boolean('is_active')->default(1);
            $table->string('created_by');
            $table->string('updated_by');

            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('investment_plan_code','ipc_ipc_foreign')->references('investment_plan_code')->on('investment_plans');

        });

        DB::statement('ALTER Table investment_plan_commission add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('investment_plan_commission');
    }
}
