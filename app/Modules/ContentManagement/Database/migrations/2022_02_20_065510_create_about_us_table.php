<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateAboutUsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aboutus', function (Blueprint $table) {
            $table->string('aboutUs_code')->unique()->primary();
            $table->string('page_image');
            $table->string('company_name');
            $table->longText('company_description');
            $table->string('ceo_name');
            $table->longText('message_from_ceo');
            $table->string('ceo_image');
            $table->boolean('is_active')->default(0);
            $table->string('created_by');
            $table->string('updated_by');
            $table->softDeletes();

            $table->timestamps();
        });
        DB::statement('ALTER Table aboutus add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT First');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aboutus');
    }
}
