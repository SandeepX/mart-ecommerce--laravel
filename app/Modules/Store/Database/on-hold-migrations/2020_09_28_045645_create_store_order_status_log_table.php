<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateStoreOrderStatusLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_order_status_log', function (Blueprint $table) {
            $table->string('store_order_status_log_code')->unique()->primary('srore_order_status_log_code');
            $table->string('store_order_code');
            $table->enum('status', ['pending','dispatched','processing','accepted','received','cancelled','partially-accepted']);
            $table->date('status_update_date');
            $table->string('updated_by');
            $table->longText('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('store_order_code')->references('store_order_code')->on('store_orders');
            $table->foreign('updated_by')->references('user_code')->on('users');

        });
        DB::statement('ALTER Table store_order_status_log add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_order_status_log');
    }
}
