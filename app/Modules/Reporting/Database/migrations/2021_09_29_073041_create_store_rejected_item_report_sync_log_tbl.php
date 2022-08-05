<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreRejectedItemReportSyncLogTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_rejected_item_report_sync_log', function (Blueprint $table) {

            $table->string('rejected_item_report_sync_log_code');
            $table->primary(['rejected_item_report_sync_log_code'],'rirsl_primary');
            $table->enum('order_type',['normal_order','preorder']);
            $table->dateTime('sync_started_at');
            $table->dateTime('sync_ended_at')->nullable();
            $table->string('synced_orders');
            $table->integer('synced_orders_count');
            $table->enum('sync_status',['success','failed','pending']);
            $table->text('sync_remarks');
            $table->timestamps();
        });
        DB::statement('ALTER Table store_rejected_item_report_sync_log add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_rejected_item_report_sync_log');
    }
}
