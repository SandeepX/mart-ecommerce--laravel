<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreBalanceFreezes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_balance_freezes', function (Blueprint $table) {
            $table->string('store_balance_freeze_code');
            $table->string('store_code');
            $table->double('amount',10,2);
            $table->string('source');
            $table->string('source_code');
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->primary(['store_balance_freeze_code'],'pk_sbf_sbfc');
            $table->foreign('store_code')->references('store_code')->on('stores_detail');
        });
        DB::statement('ALTER Table store_balance_freezes add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_balance_freezes');
    }
}
