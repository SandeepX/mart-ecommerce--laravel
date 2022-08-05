<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ip_accesses', function (Blueprint $table) {

            $table->string('ip_access_code')->unique()->primary();
            $table->string('ip_name')->unique(); // auth user
            $table->string('ip_address')->unique();
            $table->boolean('is_allowed')->default(1);
            $table->string('created_by');
            $table->string('updated_by');

            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
        });

        DB::statement('ALTER Table ip_accesses add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ip_accesses');
    }
}
