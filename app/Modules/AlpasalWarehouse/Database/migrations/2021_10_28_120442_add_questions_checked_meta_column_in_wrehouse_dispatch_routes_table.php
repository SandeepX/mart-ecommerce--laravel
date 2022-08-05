<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQuestionsCheckedMetaColumnInWrehouseDispatchRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_dispatch_routes', function (Blueprint $table) {
            $table->json('question_checked_meta')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_dispatch_routes', function (Blueprint $table) {
            $table->dropColumn('question_checked_meta');
        });
    }
}
