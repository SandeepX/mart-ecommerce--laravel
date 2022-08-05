<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPanVatTypeAndNoColumnInStoresDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores_detail', function (Blueprint $table) {

            $table->enum('pan_vat_type', ['pan','vat'])->default('pan')->after('store_logo');
            $table->string('pan_vat_no')->after('pan_vat_type')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores_detail', function (Blueprint $table) {
            $table->dropColumn([
                'pan_vat_type','pan_vat_no'
            ]);
        });
    }
}
