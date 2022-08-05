<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPackagingHistoryTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_packaging_history', function (Blueprint $table) {

            $table->string('product_packaging_history_code');
            $table->string('product_code');
            $table->string('product_variant_code')->nullable();
            $table->string('micro_unit_code');
            $table->string('unit_code');
            $table->string('macro_unit_code')->nullable();
            $table->string('super_unit_code')->nullable();
            $table->decimal('micro_to_unit_value',10,2);
            $table->decimal('unit_to_macro_value',10,2)->nullable();
            $table->decimal('macro_to_super_value',10,2)->nullable();
            $table->string('created_by')->comment('was created by');
            $table->string('updated_by')->nullable()->comment('put in history by');
            $table->date('from_date');
            $table->date('to_date')->nullable();
            $table->timestamps();

            $table->primary(['product_packaging_history_code'],'pk_pph_pphc');
            $table->foreign('product_code')->references('product_code')->on('products_master');
            $table->foreign('product_variant_code')->references('product_variant_code')->on('product_variants');
            $table->foreign('micro_unit_code')->references('package_code')->on('package_types');
            $table->foreign('unit_code')->references('package_code')->on('package_types');
            $table->foreign('macro_unit_code')->references('package_code')->on('package_types');
            $table->foreign('super_unit_code')->references('package_code')->on('package_types');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');

        });
        DB::statement('ALTER Table product_packaging_history add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_packaging_history');
    }
}
