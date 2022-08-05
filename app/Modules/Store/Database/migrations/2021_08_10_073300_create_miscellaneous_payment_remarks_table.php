<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateMiscellaneousPaymentRemarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('miscellaneous_payment_remarks', function (Blueprint $table) {

            $table->string('miscellaneous_payment_remark_code');
            $table->string('store_misc_payment_code');
            $table->longText('remark');
            $table->string('created_by');
            $table->timestamps();

            $table->primary('miscellaneous_payment_remark_code','mpr_mprc_p');
            $table->foreign('store_misc_payment_code')->references('store_misc_payment_code')->on('store_miscellaneous_payments');
            $table->foreign('created_by')->references('user_code')->on('users');
        });

        DB::statement('ALTER Table miscellaneous_payment_remarks add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('miscellaneous_payment_remarks');
    }
}
