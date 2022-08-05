<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagerPayPerVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager_pay_per_visits', function (Blueprint $table) {
            $table->string('manager_pay_per_visit_code');
            $table->string('manager_code');
            $table->double('amount');
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();

            $table->primary('manager_pay_per_visit_code','mppv_mppvc');
            $table->unique('manager_code','uk_mppv_mc');
            $table->foreign('manager_code')->references('manager_code')->on('managers_detail');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table manager_pay_per_visits add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager_pay_per_visits');
    }
}
