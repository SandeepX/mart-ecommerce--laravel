<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmiManagerAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('smi_manager_attendances', function (Blueprint $table) {
            $table->string('msmi_attendance_code')->primary();
            $table->string('msmi_code');
            $table->date('attendance_date');
            $table->enum('status',['absent','present'])->default('present');
            $table->text('remarks')->nullable();

            $table->string('created_by');
            $table->string('updated_by');

            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('msmi_code')->references('msmi_code')->on('manager_smi');

        });
        DB::statement('ALTER Table smi_manager_attendances add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('smi_manager_attendances');
    }
}
