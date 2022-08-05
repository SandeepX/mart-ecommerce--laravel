<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemoPaymentsTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demo_payments', function (Blueprint $table) {
            $table->bigIncrements('txn_id');
            $table->date('txn_date');
            $table->string('txn_currency');
            $table->string('txn_amount');
            $table->string('reference_id');
            $table->string('remarks');
            $table->string('particulars');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demo_payments');
    }
}
