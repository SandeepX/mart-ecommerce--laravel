<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_message', function (Blueprint $table) {
            $table->string('store_message_code')->unique()->primary();
            $table->string('parent_id')->nullable();
            $table->string('department');
            $table->string('sender_code');
            $table->string('receiver_code')->nullable();
            $table->string('store_message_file')->nullable();
            $table->string('subject');
            $table->longText('message');
            $table->ipAddress('sender_ip')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('sender_code')->references('user_code')->on('users');
            $table->foreign('receiver_code')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table store_message add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_message');
    }
}
