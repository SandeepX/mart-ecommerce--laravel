<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateStoreVisitClaimScanRedirectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_visit_claim_scan_redirections', function (Blueprint $table) {
            $table->string('store_visit_claim_scan_redirection_code');
            $table->string('title');
            $table->string('image');
            $table->string('app_page')->nullable();
            $table->string('external_link')->nullable();
            $table->string('is_active')->default(0);
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();

            $table->primary('store_visit_claim_scan_redirection_code','pk_svcsr_svcsrc');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table store_visit_claim_scan_redirections add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_visit_claim_scan_redirections');
    }
}
