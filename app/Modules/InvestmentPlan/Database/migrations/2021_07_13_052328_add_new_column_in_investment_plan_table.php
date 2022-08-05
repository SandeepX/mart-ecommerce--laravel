<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnInInvestmentPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investment_plans', function (Blueprint $table) {
            $table->string('ip_type_code')->nullable()->after('investment_plan_code');
            $table->double('paid_up_capital',20,10)->nullable()->after('ip_type_code');
            $table->integer('per_unit_share_price')->nullable()->after('paid_up_capital');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('investment_plans', function (Blueprint $table) {
            //
        });
    }
}
