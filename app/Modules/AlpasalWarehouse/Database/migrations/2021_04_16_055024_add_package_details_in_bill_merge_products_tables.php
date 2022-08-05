<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPackageDetailsInBillMergeProductsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bill_merge_product', function (Blueprint $table) {
            $table->string('package_code')->after('product_variant_code')->nullable();
            $table->string('product_packaging_history_code')->after('package_code')->nullable();

            $table->foreign('package_code')->references('package_code')->on('package_types');
            $table->foreign('product_packaging_history_code')
                ->references('product_packaging_history_code')->on('product_packaging_history');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bill_merge_product', function (Blueprint $table) {
            $table->dropColumn(['package_code','product_packaging_history_code']);
        });
    }
}
