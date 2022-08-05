<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateActionVerificationQuestionnareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action_verification_questionnaire', function (Blueprint $table) {
            $table->string('avq_code')->primary();
            $table->enum('action',['miscellaneous_payment_verification','dispatch_route_verification']);
            $table->enum('entity',['balance','orders']);
            $table->text('question');
            $table->boolean('is_active')->default(0);
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table action_verification_questionnaire add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('action_verification_questionnaire');
    }
}
