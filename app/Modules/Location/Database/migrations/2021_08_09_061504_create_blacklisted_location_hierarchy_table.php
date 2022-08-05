<?php


use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlacklistedLocationHierarchyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blacklisted_location_hierarchy', function (Blueprint $table) {
            $table->string('blacklisted_location_hierarchy_code')->unique('blhC');
            $table->primary(['blacklisted_location_hierarchy_code'], 'blhC_primary');

            $table->string('location_code');
            $table->enum('purpose', ['store-registration'])->default('store-registration');
            $table->boolean('status');
            $table->string('created_by');
            $table->timestamps();

            $table->foreign('location_code')->references('location_code')->on('location_hierarchy');
            $table->foreign('created_by')->references('user_code')->on('users');
        });

        DB::statement('ALTER Table blacklisted_location_hierarchy add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blacklisted_location_hierarchy');
    }
}

