<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlpasalWarehouseTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alpasal_warehouse_types', function (Blueprint $table) {
            $table->string('warehouse_type_code')->unique()->primary();
            $table->string('warehouse_type_name');
            $table->string('slug')->unique()->index();
            $table->string('remarks')->nullable();
            $table->string('warehouse_type_key')->unique();
            $table->boolean('is_closed');
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table alpasal_warehouse_types add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alpasal_warehouse_types');
    }
}
