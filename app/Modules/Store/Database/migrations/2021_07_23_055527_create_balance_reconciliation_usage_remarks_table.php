<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBalanceReconciliationUsageRemarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_reconciliation_usage_remarks', function (Blueprint $table) {
            $table->string('balance_reconciliation_usage_remark_code');
            $table->string('balance_reconciliation_usages_code');
            $table->longText('remark');
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();

            $table->unique('balance_reconciliation_usage_remark_code','brur_brurc_u');
            $table->primary('balance_reconciliation_usage_remark_code','brur_brurc_p');
            $table->foreign('balance_reconciliation_usages_code','bru_bruc_f')->references('balance_reconciliation_usages_code')->on('balance_reconciliation_usages');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table balance_reconciliation_usage_remarks add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('balance_reconciliation_usage_remarks');
    }
}
