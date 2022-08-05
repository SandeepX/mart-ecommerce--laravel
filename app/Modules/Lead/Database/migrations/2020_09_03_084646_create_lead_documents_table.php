<?php

use App\Modules\Lead\Models\LeadDocument;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadDocumentsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_documents', function (Blueprint $table) {
            $table->string('lead_document_code')->unique()->primary();
            $table->string('lead_code');
            $table->enum('document_type',array_keys(config('lead-document-types')));
            $table->string('document_file');
            $table->string('remarks')->nullable();
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');

            $table->foreign('lead_code')->references('lead_code')->on('leads_detail');
        });

        DB::statement('ALTER Table lead_documents add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_documents');
    }
}
