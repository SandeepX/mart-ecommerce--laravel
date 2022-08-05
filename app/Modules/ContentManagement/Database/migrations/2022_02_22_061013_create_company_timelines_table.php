<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateCompanyTimelinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_timelines', function (Blueprint $table) {
            $table->string('company_timeline_code')->unique()->primary();
            $table->string('year')->unique();
            $table->string('title');
            $table->longText('description');
            $table->boolean('is_active')->default(0);
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();
        });
        DB::statement('ALTER Table company_timelines add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT First');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_timelines');
    }
}
