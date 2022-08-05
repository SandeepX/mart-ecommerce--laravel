<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdminStatusColumnToInvestmentPlanSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investment_plan_subscriptions', function (Blueprint $table) {
            $table->enum('admin_status',['pending','accepted','rejected'])->default('pending')
                ->after('is_active');
            $table->longText('admin_remark')->nullable()->after('admin_status');
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
