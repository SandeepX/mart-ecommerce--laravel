<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseStoreGroupsTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_store_groups', function (Blueprint $table) {
            $table->string('wh_store_group_code');
            $table->string('warehouse_code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('group_basis',['route-default'])->default('route-default');
            $table->boolean('is_active')->default(1);
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->primary(['wh_store_group_code'],'pk_wsg_wsgc');
            $table->unique(['name'],'uq_wsg_name');
            $table->foreign('warehouse_code')->references('warehouse_code')->on('warehouses');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
        });

        DB::statement('ALTER Table warehouse_store_groups add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_store_groups');
    }
}
