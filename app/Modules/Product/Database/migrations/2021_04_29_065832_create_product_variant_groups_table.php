<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductVariantGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variant_groups', function (Blueprint $table) {
            $table->string('product_variant_group_code')->unique()->primary();
            $table->string('product_code');
            $table->string('group_name');
            $table->string('group_variant_value_code');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('product_code')->references('product_code')->on('products_master');
        });
        DB::statement('ALTER Table product_variant_groups add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variant_groups');
    }
}
