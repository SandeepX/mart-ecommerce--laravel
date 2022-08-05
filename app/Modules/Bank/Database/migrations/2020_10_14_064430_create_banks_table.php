<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->string('bank_code')->unique()->primary();
            $table->string('bank_name');
            $table->string('slug')->unique();
            $table->string('bank_logo');
            $table->string('remarks')->nullable();
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
        });

        Schema::table('banks',function (Blueprint $table){
            DB::statement('ALTER Table banks add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banks');
    }
}
