<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryCategoryTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_category_type', function (Blueprint $table) {
            $table->string('category_code');
            $table->string('category_type_code');

            $table->foreign('category_code')->references('category_code')->on('category_master');
            $table->foreign('category_type_code')->references('category_type_code')->on('category_types');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_category_type');
    }
}
