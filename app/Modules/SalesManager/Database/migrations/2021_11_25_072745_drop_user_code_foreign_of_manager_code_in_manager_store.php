<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropUserCodeForeignOfManagerCodeInManagerStore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manager_store', function (Blueprint $table) {
            $table->dropForeign('manager_store_manager_code_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manager_store', function (Blueprint $table) {
            $table->foreign('manager_code')->references('user_code')->on('users');
        });
    }
}
