<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnBannerImageInWarehousePreorderListings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_preorder_listings', function (Blueprint $table) {
            $table->string('banner_image')->nullable()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_preorder_listings', function (Blueprint $table) {
            $table->dropColumn('banner_image');
        });
    }
}
