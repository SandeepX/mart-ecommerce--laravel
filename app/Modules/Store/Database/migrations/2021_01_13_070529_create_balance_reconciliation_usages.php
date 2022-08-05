<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBalanceReconciliationUsages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_reconciliation_usages', function (Blueprint $table) {
            $table->string('balance_reconciliation_usages_code');
            $table->primary(['balance_reconciliation_usages_code'],'bruc_primary');
            $table->string('balance_reconciliation_code');
            $table->string('used_for_code');
            $table->enum('used_for',['load_balance']);
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('balance_reconciliation_code','bru_brc_fk')->references('balance_reconciliation_code')->on('balance_reconciliation');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');

            $table->timestamps();
        });
        DB::statement('ALTER Table balance_reconciliation_usages add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('balance_reconcilation_usages');
    }
}
