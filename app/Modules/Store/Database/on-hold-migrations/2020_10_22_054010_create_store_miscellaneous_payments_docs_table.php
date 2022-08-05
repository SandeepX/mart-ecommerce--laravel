<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreMiscellaneousPaymentsDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_miscellaneous_payments_docs', function (Blueprint $table) {

            $table->string('store_payment_doc_code')->unique()->primary();
            $table->string('store_misc_payment_code');
            $table->string('document_type');
            $table->string('file_name');

            $table->timestamps();

            $table->foreign('store_misc_payment_code','doc_payment_foreign')->references('store_misc_payment_code')->on('store_miscellaneous_payments');
        });

        DB::statement('ALTER Table store_miscellaneous_payments_docs add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_miscellaneous_payments_docs');
    }
}
