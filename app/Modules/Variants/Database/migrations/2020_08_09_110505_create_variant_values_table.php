<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVariantValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('variant_values', function (Blueprint $table) {
            $table->string('variant_code', 6);
            $table->string('variant_value_name', 20);
            $table->string('variant_value_code', 20)->unique()->primary();
            $table->string('slug')->unique();
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
            $table->foreign('variant_code')->references('variant_code')->on('variants');



        });

        Schema::table('variant_values',function (Blueprint $table){
            DB::statement('ALTER Table variant_values add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attribute_values');
    }
}
