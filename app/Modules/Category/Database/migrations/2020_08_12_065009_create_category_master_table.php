<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_master', function (Blueprint $table) {
            $table->string('category_code', 20)->unique()->primary();
            $table->string('category_name', 40);
            $table->string('slug')->unique();
            $table->string('upper_category_code', 20)->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');

        });

        Schema::table('category_master', function (Blueprint $table) {
            $table->foreign('upper_category_code')->references('category_code')->on('category_master');
        });

        DB::statement('ALTER Table category_master add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
        // DB::statement('ALTER Table category_master add FOREIGN KEY (upper_category_code) REFERENCES category_master(category_code)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_master');
    }
}
