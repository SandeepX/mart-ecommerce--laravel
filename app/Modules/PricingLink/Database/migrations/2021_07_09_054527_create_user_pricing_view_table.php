<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPricingViewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_pricing_view', function (Blueprint $table) {
            $table->string('user_pricing_view_code')->unique()->primary();
            $table->string('pricing_master_code');
            $table->string('mobile_number');
            $table->string('full_name');
            $table->string('location_code')->nullable();
            $table->boolean('is_verified')->default(0);
            $table->timestamps();

            $table->foreign('pricing_master_code')->references('pricing_master_code')->on('pricing_master');
            $table->foreign('location_code')->references('location_code')->on('location_hierarchy');
        });
        DB::statement('ALTER Table user_pricing_view add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_pricing_view');
    }
}
