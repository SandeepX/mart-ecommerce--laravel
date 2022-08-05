<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPackageDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_package_details', function (Blueprint $table) {
            $table->string('product_package_detail_code')->unique()->primary();
            $table->string('product_code');
            $table->string('package_code');
            $table->double('package_weight')->nullable();
            $table->double('package_length')->nullable();
            $table->double('package_height')->nullable();
            $table->double('package_width')->nullable();
            $table->integer('units_per_package')->nullable();
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
            $table->foreign('product_code')->references('product_code')->on('products_master');
            $table->foreign('package_code')->references('package_code')->on('package_types');
        });
        DB::statement('ALTER Table product_package_details add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT First');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_package_details');
    }
}
