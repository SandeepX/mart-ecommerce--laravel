<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHasMatchedColumnInStoreMiscellaneousPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_miscellaneous_payments', function (Blueprint $table) {
            $table->boolean('has_matched')->default(0)->after('remarks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_miscellaneous_payments', function (Blueprint $table) {
            $table->dropColumn('has_matched');
        });
    }
}
