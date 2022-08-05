<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remits', function (Blueprint $table) {

            $table->string('remit_code')->unique()->primary();
            $table->string('remit_name')->unique();
            $table->string('remit_slug')->unique();
            $table->string('remit_logo')->nullable();
            $table->boolean('is_active')->default(1);

            $table->timestamps();
        });

        DB::statement('ALTER Table remits add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remits');
    }
}
