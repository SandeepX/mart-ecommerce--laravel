<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesManagerRegistrationStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_manager_registration_status', function (Blueprint $table) {
            $table->string('sales_manager_registration_status_code');
            $table->string('user_code');
            $table->enum('status',['pending','processing','approved','rejected'])->default('pending');
            $table->string('assigned_area_code')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->primary(['sales_manager_registration_status_code'],'pk_smrs_ursc');
            $table->foreign('user_code')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table sales_manager_registration_status add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_manager_registration_status');
    }
}


