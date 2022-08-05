<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateFaqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->string('faq_code')->unique()->primary();
            $table->string('question');
            $table->longText('answer');
            $table->integer('priority');
            $table->boolean('is_active')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement('ALTER Table faqs add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT First');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faqs');
    }
}
