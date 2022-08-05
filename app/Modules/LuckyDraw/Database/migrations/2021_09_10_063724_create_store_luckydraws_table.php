<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreLuckydrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_luckydraws', function (Blueprint $table) {
            $table->string('store_luckydraw_code')->unique()->primary();
            $table->string('luckydraw_name');
            $table->string('slug')->unique()->index();
            $table->enum('type',['cash','goods'])->default('cash');
            $table->string('prize');
            $table->string('image')->nullable();
            $table->double('eligibility_sales_amount');
            $table->string('days');
            $table->dateTime('opening_time')->nullable();
            $table->string('pickup_time')->nullable();
            $table->text('youtube_link')->nullable();
            $table->json('terms')->nullable();
            $table->enum('status',['pending','open','closed'])->default('pending');
            $table->longText('remarks')->nullable();
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');

        });
        DB::statement('ALTER Table store_luckydraws add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_luckydraws');
    }
}
