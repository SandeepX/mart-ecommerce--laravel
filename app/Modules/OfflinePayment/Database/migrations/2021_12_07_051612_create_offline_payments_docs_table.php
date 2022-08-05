<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflinePaymentsDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_payments_docs', function (Blueprint $table) {
            $table->string('offline_payment_docs_code');
            $table->string('offline_payment_code');
            $table->string('document_type');
            $table->string('file_name');
            $table->timestamps();

            $table->primary('offline_payment_docs_code','pk_ofpd_ofpdc');
            $table->foreign('offline_payment_code')
                ->references('offline_payment_code')
                ->on('offline_payment_master');
        });

        DB::statement('ALTER Table offline_payments_docs add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offline_payments_docs');
    }
}
