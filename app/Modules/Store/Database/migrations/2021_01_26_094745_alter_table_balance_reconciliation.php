<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableBalanceReconciliation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('balance_reconciliation', function (Blueprint $table) {
            $table->string('transaction_no')->nullable()->change();
            $table->dropUnique('uq_BR_pbctn');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('balance_reconciliation', function (Blueprint $table) {
            $table->dropColumn('transaction_no');
        });
    }
}
