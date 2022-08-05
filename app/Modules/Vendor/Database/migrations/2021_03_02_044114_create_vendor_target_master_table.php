<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorTargetMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_target_master', function (Blueprint $table) {
            $table->string('vendor_target_master_code');
            $table->string('name');
            $table->string('slug')->unique()->index();
            $table->string('vendor_code');
            $table->string('province_code');
            $table->string('district_code');
            $table->string('municipality_code');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->boolean('is_active')->default(0);
            $table->enum('status',['pending','processing','accepted','rejected'])->default('pending');
            $table->text('remark');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->primary(['vendor_target_master_code'],'pk_vtm_vtmc');
            $table->foreign('vendor_code')->references('vendor_code')->on('vendors_detail');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
        });

        DB::statement('ALTER Table vendor_target_master add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_target_master');
    }
}
