<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreVisitClaimRequestsByManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_visit_claim_requests_by_manager', function (Blueprint $table) {
            $table->string('store_visit_claim_request_code');
            $table->string('manager_diary_code');
            $table->enum('status',['drafted','pending','verified','rejected'])->default('drafted');
            $table->double('manager_latitude');
            $table->double('manager_longitude');
            $table->json('manager_device_info');
            $table->double('store_latitude')->nullable();
            $table->double('store_longitude')->nullable();
            $table->json('store_device_info')->nullable();
            $table->timestamp('qr_scanned_at')->nullable();
            $table->string('responded_by')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->longText('remarks')->nullable();
            $table->string('visit_image')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->double('pay_per_visit')->default(0);
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();

            $table->primary('store_visit_claim_request_code','pk_svcrm_svcrc');
            $table->foreign('manager_diary_code')->references('manager_diary_code')->on('manager_diaries');
            $table->foreign('responded_by')->references('user_code')->on('users');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table store_visit_claim_requests_by_manager add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_visit_claim_requests_by_manager');
    }
}
