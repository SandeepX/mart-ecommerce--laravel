<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletTransactionPurposeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      //  dd('1');
            Schema::create('wallet_transaction_purpose', function (Blueprint $table) {
                $table->string('wallet_transaction_purpose_code');
                $table->string('purpose');
                $table->enum('purpose_type',['increment','decrement']);
                $table->string('slug');
                $table->boolean('is_active');
                $table->boolean('admin_control')->default(0);
                $table->boolean('close_for_modification')->default(0);
                $table->string('user_type_code');
                $table->string('created_by');
                $table->string('updated_by');
                $table->string('deleted_by')->nullable();
                $table->softDeletes();
                $table->timestamps();

                //$table->unique('wallet_transaction_purpose_code','wtp_wtpc_u');
                $table->primary('wallet_transaction_purpose_code','wtp_wtpc_p');
                $table->unique(['slug', 'user_type_code']);
                $table->foreign('user_type_code')->references('user_type_code')->on('user_types');
                $table->foreign('created_by')->references('user_code')->on('users');
                $table->foreign('updated_by')->references('user_code')->on('users');
                $table->foreign('deleted_by')->references('user_code')->on('users');
            });
            DB::statement('ALTER Table wallet_transaction_purpose add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_transaction_purpose');
    }
}
