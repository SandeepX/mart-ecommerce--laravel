<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflinePaymentMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_payment_master', function (Blueprint $table) {
            $table->string('offline_payment_code')->primary();
            $table->enum('payment_for',['initial_registration','load_balance','investment']);
            $table->string('offline_payment_holder_namespace');
            $table->string('payment_holder_type');
            $table->string('offline_payment_holder_code');
            $table->enum('payment_type',['cash','cheque','remit','wallet','mobile_banking']);
            $table->string('deposited_by');
            $table->date('transaction_date');
            $table->string('contact_phone_no');
            $table->double('amount');
            $table->enum('verification_status',['pending','rejected','verified'])->default('pending');
            $table->string('responded_by')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->longText('remarks')->nullable();
            $table->boolean('has_matched')->default(0);
            $table->json('questions_checked_meta')->nullable();
            $table->string('reference_code')->nullable();
            $table->string('created_by');
            $table->timestamps();

            $table->foreign('responded_by')->references('user_code')->on('users');
            $table->foreign('created_by')->references('user_code')->on('users');
        });

        DB::statement('ALTER Table offline_payment_master add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offline_payment_master');
    }
}
