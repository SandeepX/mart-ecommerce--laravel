<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorePreorderStatusLogTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_preorder_status_log', function (Blueprint $table) {

            $table->string('store_preorder_status_log_code');
            $table->string('store_preorder_code');
            $table->enum('status', ['pending','finalized','dispatched','cancelled']);
            $table->longText('remarks')->nullable();
            $table->string('updated_by');
            $table->timestamps();

            $table->primary(['store_preorder_status_log_code'],'pk_spsl_spslc');

            $table->foreign('store_preorder_code','fk_spsl_spc')->references('store_preorder_code')->on('store_preorder');
            $table->foreign('updated_by','fk_spsl_ub')->references('user_code')->on('users');

        });

        DB::statement('ALTER Table store_preorder_status_log add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_preorder_status_log');
    }
}
