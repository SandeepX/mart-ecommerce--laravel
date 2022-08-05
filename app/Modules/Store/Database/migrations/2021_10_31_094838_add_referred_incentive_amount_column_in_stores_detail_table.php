<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferredIncentiveAmountColumnInStoresDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores_detail', function (Blueprint $table) {
            $table->double('referred_incentive_amount')->nullable()->after('has_store');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores_detail', function (Blueprint $table) {
            $table->dropColumn('referred_incentive_amount');
        });
    }
}
