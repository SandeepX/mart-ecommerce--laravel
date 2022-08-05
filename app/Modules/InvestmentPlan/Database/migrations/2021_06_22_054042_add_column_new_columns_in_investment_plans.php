<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnNewColumnsInInvestmentPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investment_plans', function (Blueprint $table) {
            $table->double('interest_rate')->after('price_end_range');
            $table->text('description')->nullable()->after('interest_rate');
            $table->text('terms')->nullable()->after('description');
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
