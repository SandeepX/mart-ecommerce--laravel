<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobApplicationDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_application_documents', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('job_application_code', 20);//foreign
            $table->string('document');
            $table->enum('document_type',['cv','cover_letter']);
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('job_application_code')->references('application_code')->on('job_applications')->onDelete('no action');
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
        Schema::dropIfExists('job_application_documents');
    }
}
