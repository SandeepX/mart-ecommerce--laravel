<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorePreorderEarlyFinalizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_preorder_early_finalization', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('store_preorder_code');
            $table->timestamp('early_finalization_date');
            $table->longText('early_finalization_remarks');
            $table->string('early_finalized_by');
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();

            $table->foreign('store_preorder_code')->references('store_preorder_code')
                    ->on('store_preorder');
            $table->foreign('early_finalized_by')->references('user_code')
                ->on('users');
            $table->foreign('created_by')->references('user_code')
                ->on('users');
            $table->foreign('updated_by')->references('user_code')
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
        Schema::dropIfExists('store_preorder_early_finalization');
    }
}
