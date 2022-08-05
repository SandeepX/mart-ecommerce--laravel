<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillMergeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_merge_details', function (Blueprint $table) {
            $table->string('bill_merge_details_code')->primary();
            $table->string('bill_merge_master_code');
            $table->enum('bill_type', ['cart','preorder'])->default('cart');
            $table->string('bill_code');
            $table->timestamps();

            $table->foreign('bill_merge_master_code')->references('bill_merge_master_code')->on('bill_merge_master');
        });
        DB::statement('ALTER Table bill_merge_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_merge_details');
    }
}
