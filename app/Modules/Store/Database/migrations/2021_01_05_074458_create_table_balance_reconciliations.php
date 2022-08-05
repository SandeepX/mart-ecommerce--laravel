<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBalanceReconciliations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_reconciliation', function (Blueprint $table) {

            $table->string('balance_reconciliation_code')->primary();
            $table->enum('transaction_type',['withdraw','deposit']);
            $table->enum('payment_method',['bank','remit','digital_wallet']);
            $table->string('payment_body_code',200);
            $table->string('transaction_no');
            $table->double('transaction_amount')->default(00.00);
            $table->string('transacted_by');
            $table->longtext('description')->nullable();
            $table->date('transaction_date');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->enum('status',['used','unused'])->default('unused');

            $table->unique(['payment_body_code','transaction_no'],'uq_BR_pbctn');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');

            $table->timestamps();
        });
        DB::statement('ALTER Table balance_reconciliation add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('balance_reconciliation');
    }
}
