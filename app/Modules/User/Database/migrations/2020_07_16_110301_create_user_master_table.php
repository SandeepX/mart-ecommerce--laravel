<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('user_code')->unique()->primary();
            $table->string('user_type_code');
            $table->string('name');
            $table->string('login_email')->unique();
            $table->string('login_phone')->unique();
            $table->string('password');

            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->text('remarks')->nullable();
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();



        });

        Schema::table('users', function (Blueprint $table) {
           $table->foreign('user_type_code')->references('user_type_code')->on('user_types');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
        });

        Schema::table('users',function (Blueprint $table){
            DB::statement('ALTER Table users add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
