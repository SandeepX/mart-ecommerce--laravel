<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_docs', function (Blueprint $table) {
            $table->string('user_doc_code');
            $table->string('user_code');
            $table->string('doc_name');
            $table->string('doc');
            $table->boolean('is_verified')->default(0);
            $table->string('verified_by')->nullable();
            $table->timestamps();

            $table->primary(['user_doc_code'],'pk_ud_udc');
            $table->foreign('user_code')->references('user_code')->on('users');
            $table->foreign('verified_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table user_docs add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_docs');
    }
}
