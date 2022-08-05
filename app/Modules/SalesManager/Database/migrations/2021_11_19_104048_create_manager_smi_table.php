<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagerSmiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager_smi', function (Blueprint $table) {
            $table->string('msmi_code')->primary();
            $table->string('manager_code');
            $table->enum('status',['pending','approved','rejected'])->default('pending');
            $table->boolean('is_active')->default(0);
            $table->boolean('allow_edit')->default(0);
            $table->string('edit_allowed_by')->nullable();


            $table->timestamps();
            $table->string('created_by');
            $table->string('updated_by');
            $table->softDeletes();
            $table->string('deleted_by')->nullable();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
            $table->foreign('edit_allowed_by')->references('user_code')->on('users');
            $table->foreign('manager_code')->references('manager_code')->on('managers_detail');
        });
        DB::statement('ALTER Table manager_smi add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager_smi');
    }
}
