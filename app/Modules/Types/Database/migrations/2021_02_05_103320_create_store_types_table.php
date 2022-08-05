<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_types', function (Blueprint $table) {
            $table->string('store_type_code')->unique();
            $table->primary('store_type_code');
            $table->string('store_type_name');
            $table->string('store_type_slug')->unique();
            $table->boolean('is_active')->default(1);
            $table->string('created_by');
            $table->string('deleted_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('user_code')->on('users');
        });
        Schema::table('store_types',function (Blueprint $table){
            DB::statement('ALTER Table store_types add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_types');
    }
}
