<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CeateStoreOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_orders', function (Blueprint $table) {
            $table->string('store_order_code')->unique()->primary();
            $table->double('total_price')->comment('total price without vat');
            $table->string('store_code');
            $table->string('user_code');
            $table->boolean('payment_status')->default(0);
            $table->enum('delivery_status', ['pending','dispatched','processing','accepted','received','cancelled','partially-accepted']);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('store_code')->references('store_code')->on('stores_detail');
            $table->foreign('user_code')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table store_orders add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_orders');
    }
}
