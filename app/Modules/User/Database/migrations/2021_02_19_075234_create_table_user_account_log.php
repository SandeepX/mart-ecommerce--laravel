<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserAccountLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_account_log', function (Blueprint $table) {
            $table->string('user_account_log_code')->unique()->primary();
            $table->enum('account_log_type',['super-admin','admin','vendor','store','warehouse-admin','warehouse-user']);
            $table->string('account_code');
            $table->text('reason');
            $table->enum('account_status',['suspend','permanently_banned']);
            $table->string('banned_by');
            $table->string('unbanned_by')->nullable();
            $table->string('updated_by');
            $table->boolean('is_unbanned')->default(0);
            $table->boolean('is_closed')->default(0);
            $table->timestamps();

            $table->foreign('account_code')->references('user_code')->on('users');
            $table->foreign('banned_by')->references('user_code')->on('users');
            $table->foreign('unbanned_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table user_account_log add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_account_log');
    }
}
