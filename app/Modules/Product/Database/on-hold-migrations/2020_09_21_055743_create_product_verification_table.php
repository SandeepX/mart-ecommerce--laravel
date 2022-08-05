<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateProductVerificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_verification', function (Blueprint $table) {
            $table->string('verification_code')->unique()->primary();
            $table->string('product_code');
            $table->string('user_code');
            $table->enum('verification_status', ['approved', 'rejected', 'on hold']);
            $table->date('verification_date');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('product_code')->references('product_code')->on('products_master');
            $table->foreign('user_code')->references('user_code')->on('users');

        });
        DB::statement('ALTER Table product_verification add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT First');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_verification');
    }
}
