<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTransactionDateInStoreMiscellaneousPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_miscellaneous_payments', function (Blueprint $table) {
            $table->date('transaction_date')->after('purpose');
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
            $table->dropColumn('transaction_date');
        });
    }
}
