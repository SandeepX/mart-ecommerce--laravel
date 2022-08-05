<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorePackageHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_package_history', function (Blueprint $table) {
            $table->string('store_package_history_code')->unique()->primary();
            $table->string('store_code');
            $table->string('store_type_code');
            $table->string('store_type_package_history_code');
            $table->timestamp('from_date');
            $table->timestamp('to_date');
            $table->longText('remarks');
            $table->string('created_by');
            $table->timestamps();

            $table->foreign('store_code')->references('store_code')->on('stores_detail');
            $table->foreign('store_type_code')->references('store_type_code')->on('store_types');
            $table->foreign('store_type_package_history_code','sph_stphc')->references('store_type_package_history_code')->on('store_type_package_history');
            $table->foreign('created_by')->references('user_code')->on('users');
        });

        DB::statement('ALTER Table store_package_history add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_package_history');
    }
}
