<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdminUpdatedByToStorePreorderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_preorder_details', function (Blueprint $table) {
            //
            $table->string('admin_updated_by')->after('updated_by')->nullable();
            $table->timestamp('admin_updated_at')->after('admin_updated_by')->nullable();

            $table->foreign('admin_updated_by','fk_spd_aub')->references('user_code')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_preorder_details', function (Blueprint $table) {
            //
            $table->dropColumn('admin_updated_by');
            $table->dropColumn('admin_updated_at');
        });
    }
}
