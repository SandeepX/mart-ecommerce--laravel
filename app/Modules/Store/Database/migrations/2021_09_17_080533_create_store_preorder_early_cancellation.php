<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorePreorderEarlyCancellation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_preorder_early_cancellation', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('store_preorder_code');
            $table->timestamp('early_cancelled_date');
            $table->longText('early_cancelled_remarks');
            $table->string('early_cancelled_by');

            $table->timestamps();

            $table->foreign('store_preorder_code')->references('store_preorder_code')
                ->on('store_preorder');
            $table->foreign('early_cancelled_by')->references('user_code')
                ->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_preorder_early_cancellation');
    }
}
