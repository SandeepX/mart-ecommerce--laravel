<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflinePaymentRemarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_payment_remarks', function (Blueprint $table) {
            $table->string('offline_payment_remark_code');
            $table->string('offline_payment_code');
            $table->longText('remark');
            $table->string('created_by');
            $table->timestamps();

            $table->primary('offline_payment_remark_code','pk_ofpr_ofprc');

            $table->foreign('offline_payment_code')
                ->references('offline_payment_code')
                ->on('offline_payment_master');
            $table->foreign('created_by')
                ->references('user_code')
                ->on('users');
        });

        DB::statement('ALTER Table offline_payment_remarks add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offline_payment_remarks');
    }
}
