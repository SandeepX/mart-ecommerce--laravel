<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorePackageUpgradeRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_package_upgrade_request', function (Blueprint $table) {
            $table->string('store_package_upgrade_request_code');
            $table->string('store_code');
            $table->string('requested_store_type');
            $table->string('requested_package_type');
            $table->enum('status',['pending','accepted','rejected'])->default('pending');
            $table->longText('remark')->nullable();
            $table->string('requested_by');
            $table->string('responded_by')->nullable();
            $table->timestamps();

            $table->primary(['store_package_upgrade_request_code'],'pk_spur_spurc');
            $table->foreign('store_code')->references('store_code')->on('stores_detail');
            $table->foreign('requested_store_type')->references('store_type_code')->on('store_types');
            $table->foreign('requested_package_type')->references('store_type_package_history_code')->on('store_type_package_history');
            $table->foreign('requested_by')->references('user_code')->on('users');
            $table->foreign('responded_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table store_package_upgrade_request add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_package_upgrade_request');
    }
}
