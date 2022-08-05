<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreOrderOfflinePaymentDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_order_offline_payment_docs', function (Blueprint $table) {

            $table->string('payment_doc_code')->unique()->primary();
            $table->string('store_order_offline_payment_code');
            $table->string('document_type');
            $table->string('file_name');

            $table->timestamps();

            $table->foreign('store_order_offline_payment_code','store_order_offline_code')->references('store_offline_payment_code')->on('store_order_offline_payments');

        });
        DB::statement('ALTER Table store_order_offline_payment_docs add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_order_offline_payment_docs');
    }
}
