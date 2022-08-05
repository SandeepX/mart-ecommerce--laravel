<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMostPopularProductsSyncLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('most_popular_products_sync_log', function (Blueprint $table) {
            $table->string('most_popular_products_sync_log_code');
            $table->timestamp('sync_started_at');
            $table->timestamp('sync_ended_at')->nullable();
            $table->enum('sync_status',['pending','success','failed'])->default('pending');
            $table->longText('sync_remarks')->nullable();
            $table->timestamps();

            $table->primary('most_popular_products_sync_log_code','pk_mppsl_mppslc');
        });

        DB::statement('ALTER Table most_popular_products_sync_log add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('most_popular_products_sync_log');
    }
}
