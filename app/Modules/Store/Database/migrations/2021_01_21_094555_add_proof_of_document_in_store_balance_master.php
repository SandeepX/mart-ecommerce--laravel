<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProofOfDocumentInStoreBalanceMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_balance_master', function (Blueprint $table) {
            $table->string('proof_of_document')->nullable()->after('remarks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_balance_master', function (Blueprint $table) {
            $table->dropColumn(['proof_of_document']);
        });
    }
}
