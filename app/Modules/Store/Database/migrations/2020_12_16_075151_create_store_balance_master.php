<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreBalanceMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_balance_master', function (Blueprint $table) {

            $table->string('store_balance_master_code')->unique()->primary();
            $table->string('store_code');
            $table->double('transaction_amount')->default(00.00);

            $table->enum('transaction_type',['sales','sales_return','load_balance','withdraw','preorder']);
            $table->longtext('remarks')->nullable();
            $table->double('current_balance')->default(00.00);
            $table->string('created_by');

            $table->foreign('store_code')->references('store_code')->on('stores_detail');
            $table->foreign('created_by')->references('user_code')->on('users');

            $table->timestamps();
        });
        DB::statement('ALTER Table store_balance_master add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_balance_master');
    }
}
