<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobOpeningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_openings', function (Blueprint $table) {
            $table->string('opening_code', 20)->unique()->primary();
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->string('location');
            $table->text('description');
            $table->text('requirements');
            $table->string('salary')->nullable();
            $table->enum('job_type',['full_time','part_time','intern']);
            $table->boolean('is_active')->default(1);
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users')->onDelete('no action');
            $table->foreign('updated_by')->references('user_code')->on('users')->onDelete('no action');
            $table->foreign('deleted_by')->references('user_code')->on('users')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_openings');
    }
}
