<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductWarrantiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_warranties', function (Blueprint $table) {
            $table->string('warranty_code', 6)->unique()->primary();
            $table->string('warranty_name', 40);
            $table->string('slug')->unique();
            $table->string('remarks',60)->nullable();
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table product_warranties add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT AFTER warranty_code');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_warranties');
    }
}
