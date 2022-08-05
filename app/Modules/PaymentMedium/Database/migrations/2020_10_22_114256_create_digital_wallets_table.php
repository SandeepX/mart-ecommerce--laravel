<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDigitalWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('digital_wallets', function (Blueprint $table) {

            $table->string('wallet_code')->unique()->primary();
            $table->string('wallet_name')->unique();
            $table->string('wallet_slug')->unique();
            $table->string('wallet_logo')->nullable();
            $table->boolean('is_active')->default(1);

            $table->timestamps();
        });
        DB::statement('ALTER Table digital_wallets add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('digital_wallets');
    }
}
