<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorTargetIncentiveTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_target_incentive', function (Blueprint $table) {
            $table->string('vendor_target_incentive_code');
            $table->string('vendor_target_master_code');
            $table->string('product_code');
            $table->string('product_variant_code')->nullable();
            $table->double('starting_range');
            $table->double('end_range');
            $table->enum('incentive_type',['p','f'])->default('f');
            $table->double('incentive_value');
            $table->boolean('has_meet_target')->default(0);
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->primary(['vendor_target_incentive_code'],'pk_vti_vtic');
            $table->foreign('vendor_target_master_code','fk_vtm_wplc')->references('vendor_target_master_code')->on('vendor_target_master');
            $table->foreign('product_code')->references('product_code')->on('products_master');
            $table->foreign('product_variant_code')->references('product_variant_code')->on('product_variants');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table vendor_target_incentive add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_target_incentive');
    }
}
