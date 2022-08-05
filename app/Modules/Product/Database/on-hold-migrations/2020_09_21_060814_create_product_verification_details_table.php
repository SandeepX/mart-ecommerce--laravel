<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateProductVerificationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_verification_details', function (Blueprint $table) {
            $table->string('product_verification_detail_code')->unique()->primary('product_verification_detail_code');
            $table->string('verification_code');
            $table->enum('old_verification_status',['approved', 'rejected', 'on hold'])->nullable();
            $table->enum('new_verification_status', ['approved', 'rejected', 'on hold']);
            $table->date('old_verification_date')->nullable();
            $table->date('new_verification_date');
            $table->text('remarks');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('verification_code')->references('verification_code')->on('product_verification');

        });
        DB::statement('ALTER Table product_verification_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT First');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_verification_details');
    }
}
