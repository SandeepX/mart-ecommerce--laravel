<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToProductCollections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_collections', function (Blueprint $table) {
            DB::statement('ALTER Table product_collections add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_collections', function (Blueprint $table) {
            //
        });
    }
}
