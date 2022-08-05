<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->string('subscriber_code')->unique()->primary();
            $table->string('email')->unique();
            $table->string('token')->nullable();
            $table->boolean('is_active')->default(0);
            $table->timestamps();
        });

        Schema::table('subscribers',function (Blueprint $table){
            DB::statement('ALTER Table subscribers add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscribers');
    }
}
