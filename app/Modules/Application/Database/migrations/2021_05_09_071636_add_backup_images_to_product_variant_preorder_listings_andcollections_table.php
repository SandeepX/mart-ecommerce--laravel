<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBackupImagesToProductVariantPreorderListingsAndcollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->string('backup_image')->after('image')->nullable();
        });

        Schema::table('product_variant_images', function (Blueprint $table) {
            $table->string('backup_image')->after('image')->nullable();
        });

        Schema::table('warehouse_preorder_listings', function (Blueprint $table) {
            $table->string('backup_image')->after('banner_image')->nullable();
        });

        Schema::table('product_collections', function (Blueprint $table) {
            $table->string('backup_image')->after('product_collection_image')->nullable();
        });

        Schema::table('wh_product_collections', function (Blueprint $table) {
            $table->string('backup_image')->after('product_collection_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropColumn('backup_image');
        });
        Schema::table('product_variant_images', function (Blueprint $table) {
            $table->dropColumn('backup_image');
        });
        Schema::table('warehouse_preorder_listings', function (Blueprint $table) {
            $table->dropColumn('backup_image');
        });
        Schema::table('product_collections', function (Blueprint $table) {
            $table->dropColumn('backup_image');
        });
        Schema::table('wh_product_collections', function (Blueprint $table) {
            $table->dropColumn('backup_image');
        });
    }
}
