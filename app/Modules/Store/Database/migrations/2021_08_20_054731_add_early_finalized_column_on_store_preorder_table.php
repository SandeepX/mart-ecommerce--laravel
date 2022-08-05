<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEarlyFinalizedColumnOnStorePreorderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_preorder', function (Blueprint $table) {
            $table->boolean('early_finalized')->default(0)->after('has_merged');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_preorder', function (Blueprint $table) {
            $table->dropColumn('early_finalized');
        });
    }
}
