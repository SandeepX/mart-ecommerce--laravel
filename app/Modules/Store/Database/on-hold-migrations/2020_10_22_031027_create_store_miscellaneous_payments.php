<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreMiscellaneousPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_miscellaneous_payments', function (Blueprint $table) {
  
            $table->string('store_misc_payment_code')->unique()->primary();
            $table->string('user_code'); // auth user 
            $table->string('store_code');

            $table->enum('payment_for',['initial_registration']);
            $table->enum('payment_type',['cash','cheque','remit','wallet']);

            $table->string('deposited_by'); // any person who deposited not auth user
            $table->string('purpose');
            $table->double('amount',10,2);
            $table->string('voucher_number');
            $table->enum('verification_status',['pending','rejected','verified'])->default('pending');
            $table->string('responded_by')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->longText('remarks')->nullable();

            $table->timestamps();

            $table->foreign('user_code')->references('user_code')->on('users');
            $table->foreign('responded_by')->references('user_code')->on('users');
            $table->foreign('store_code')->references('store_code')->on('stores_detail');
            
           
        });

        DB::statement('ALTER Table store_miscellaneous_payments add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_miscellaneous_payments');
    }
}
