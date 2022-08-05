<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultValueToStoreLogoInStoresDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores_detail', function (Blueprint $table) {
            $table->string('store_logo')->default('alplogo.png')->change();
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
            $table->string('store_logo')->default('alplogo.png')->change();
        });
    }
}
