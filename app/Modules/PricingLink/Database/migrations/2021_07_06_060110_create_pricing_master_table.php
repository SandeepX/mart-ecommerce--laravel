<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricingMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricing_master', function (Blueprint $table) {
            $table->string('pricing_master_code')->unique()->primary();
            $table->string('warehouse_code');
            $table->text('link');
            $table->string('password');
            $table->dateTime('expires_at');
            $table->boolean('is_active')->default(0);
            $table->timestamps();

            $table->foreign('warehouse_code')->references('warehouse_code')->on('warehouses');
        });
        DB::statement('ALTER Table pricing_master add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pricing_master');
    }
}
