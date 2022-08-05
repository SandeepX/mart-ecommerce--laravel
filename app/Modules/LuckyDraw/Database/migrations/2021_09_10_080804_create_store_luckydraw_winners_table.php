<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreLuckydrawWinnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_luckydraw_winners', function (Blueprint $table) {
            $table->string('store_luckydraw_winner_code')->unique()->primary();
            $table->string('store_luckydraw_code');
            $table->string('store_code');
            $table->boolean('winner_eligibility');
            $table->longText('remarks')->nullable();
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('store_code')->references('store_code')->on('stores_detail');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');

        });
        DB::statement('ALTER Table store_luckydraw_winners add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_luckydraw_winners');
    }
}
