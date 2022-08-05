<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillMergeProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_merge_product', function (Blueprint $table) {
            $table->string('bill_merge_product_code')->primary();
            $table->string('bill_merge_master_code');
            $table->string('bill_merge_details_code');
            $table->string('product_code');
            $table->string('product_variant_code')->nullable();
            $table->integer('initial_order_quantity');
            $table->integer('quantity');
            $table->boolean('is_taxable')->default(0);
            $table->double('unit_rate',[8,2]);
            $table->double('subtotal',[8,2]);
            $table->enum('status', ['accepted','rejected'])->default('accepted');
            $table->timestamps();

            $table->foreign('bill_merge_master_code','fk_bmp_bmmc')->references('bill_merge_master_code')->on('bill_merge_master');
            $table->foreign('bill_merge_details_code','fk_bmd_bmdc')->references('bill_merge_details_code')->on('bill_merge_details');
            $table->foreign('product_code')->references('product_code')->on('products_master');
            $table->foreign('product_variant_code')->references('product_variant_code')->on('product_variants');
        });
        DB::statement('ALTER Table bill_merge_product add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_merge_product');
    }
}
