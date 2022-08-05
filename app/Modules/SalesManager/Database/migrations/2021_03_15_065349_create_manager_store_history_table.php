<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagerStoreHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager_store_history', function (Blueprint $table) {

            $table->string('manager_store_history_code')->primary();
            $table->string('manager_code');
            $table->string('store_code');
            $table->string('assigned_by');
            $table->timestamp('assigned_date')->nullable();
            $table->timestamp('removed_date')->nullable();

            $table->timestamps();

            $table->foreign('store_code')->references('store_code')->on('stores_detail');
            $table->foreign('manager_code')->references('user_code')->on('users');
            $table->foreign('assigned_by')->references('user_code')->on('users');
        });

        DB::statement('ALTER Table manager_store_history add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager_store_history');
    }
}
