<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflinePaymentsMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_payments_meta', function (Blueprint $table) {
            $table->string('offline_payment_meta_code');
            $table->string('offline_payment_code');
            $table->string('key');
            $table->string('value');
            $table->timestamps();

            $table->primary('offline_payment_meta_code','pk_ofpm_ofpmc');
            $table->foreign('offline_payment_code')
                    ->references('offline_payment_code')
                    ->on('offline_payment_master');
        });

        DB::statement('ALTER Table offline_payments_meta add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offline_payments_meta');
    }
}
