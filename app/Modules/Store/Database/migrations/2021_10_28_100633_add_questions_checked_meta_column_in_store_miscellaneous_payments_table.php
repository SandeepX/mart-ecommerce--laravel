<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQuestionsCheckedMetaColumnInStoreMiscellaneousPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_miscellaneous_payments', function (Blueprint $table) {
            $table->json('questions_checked_meta')->nullable()->after('has_matched');
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
            $table->dropColumn('questions_checked_meta');
        });
    }
}
