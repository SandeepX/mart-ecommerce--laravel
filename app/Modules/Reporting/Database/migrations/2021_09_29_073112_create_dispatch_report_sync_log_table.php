<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateDispatchReportSyncLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispatch_report_sync_log', function (Blueprint $table) {
            $table->string('dispatch_report_sync_log_code');
            $table->enum('order_type',['normal_order','preorder']);
            $table->timestamp('sync_started_at');
            $table->timestamp('sync_ended_at')->nullable();
            $table->longText('synced_orders')->nullable();
            $table->integer('synced_orders_count')->default(0);
            $table->enum('sync_status',['pending','success','failed'])->default('pending');
            $table->longText('sync_remarks')->nullable();
            $table->timestamps();

            $table->primary('dispatch_report_sync_log_code','pk_drsl_drslc');
        });
        DB::statement('ALTER Table dispatch_report_sync_log add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dispatch_report_sync_log');
    }
}
