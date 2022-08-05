<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateManagerDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager_docs', function (Blueprint $table) {
            $table->string('manager_doc_code')->primary();
            $table->string('manager_code');
            $table->string('doc_name');
            $table->string('doc');
            $table->boolean('is_verified')->default(0);
            $table->string('verified_by')->nullable();
            $table->string('doc_number')->nullable();
            $table->string('doc_issued_district')->nullable();
            $table->timestamps();

            $table->foreign('manager_code')->references('manager_code')->on('managers_detail');
            $table->foreign('doc_issued_district')->references('location_code')->on('location_hierarchy');
        });
        DB::statement('ALTER Table manager_docs add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager_docs');
    }
}
