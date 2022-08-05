<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRemarksToBalanceReconcilitaionUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('balance_reconciliation_usages', function (Blueprint $table) {
            $table->longText('remarks')->after('used_for')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('balance_reconciliation_usages', function (Blueprint $table) {
            $table->dropColumn('remarks');
        });
    }
}
