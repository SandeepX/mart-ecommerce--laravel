<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaticPageImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('static_page_image', function (Blueprint $table) {
            $table->string('static_page_image_code');
            $table->primary(['static_page_image_code'],'spic_primary');
            $table->string('page_name');
            $table->string('image');
            $table->boolean('is_active')->default(1);
            $table->string('created_by');
            $table->string('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table static_page_image add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT First');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('static_page_image');
    }
}
