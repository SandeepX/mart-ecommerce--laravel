<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreBalancePreorderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_balance_preorder_details', function (Blueprint $table) {
            $table->string('store_preorder_balance_code');
            $table->string('store_balance_master_code');
            $table->string('store_preorder_code');
            $table->timestamps();

            $table->primary(['store_preorder_balance_code'],'pk_sbpd_spbc');
            $table->foreign('store_balance_master_code','fk_sbpd_sbmc')->references('store_balance_master_code')->on('store_balance_master');
            $table->foreign('store_preorder_code','fk_sbpd_spc')->references('store_preorder_code')->on('store_preorder');

        });
        DB::statement('ALTER Table store_balance_preorder_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_balance_preorder_details');
    }
}
