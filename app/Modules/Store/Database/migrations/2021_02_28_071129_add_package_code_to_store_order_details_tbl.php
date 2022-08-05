<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPackageCodeToStoreOrderDetailsTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_order_details', function (Blueprint $table) {
            //
            $table->string('package_code')->after('product_variant_code')->nullable();


            $table->foreign('package_code')->references('package_code')->on('package_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_order_details', function (Blueprint $table) {
            //
            $table->dropColumn('package_code');
        });
    }
}
