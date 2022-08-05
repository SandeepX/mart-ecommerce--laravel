<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlinePaymentMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_payment_meta', function (Blueprint $table) {
            $table->string('online_payment_meta_code');
            $table->string('online_payment_code');
            $table->string('key');
            $table->string('value');
            $table->timestamps();

            $table->primary('online_payment_meta_code','pk_opm_opmc');
            $table->foreign('online_payment_code')
                    ->references('online_payment_master_code')
                    ->on('online_payment_master');
        });

        DB::statement('ALTER Table online_payment_meta add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('online_payment_meta');
    }
}
