<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreTypePackageMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_type_package_master', function (Blueprint $table) {
            $table->string('store_type_package_master_code');
            $table->string('store_type_code');
            $table->string('package_name');
            $table->string('package_slug')->unique();
            $table->longText('description');
            $table->string('image')->nullable();
            $table->double('refundable_registration_charge',[20,10]);
            $table->double('non_refundable_registration_charge',[20,10]);
            $table->double('base_investment',[20,10]);
            $table->double('annual_purchasing_limit',[20,10]);
            $table->double('referal_registration_incentive_amount',[20,10]);
            $table->boolean('is_active')->default(1);
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->primary(['store_type_package_master_code'],'pk_stpm_stpmc');
            $table->foreign('store_type_code')->references('store_type_code')->on('store_types');
            $table->foreign('created_by')->references('user_code')->on('users');
            $table->foreign('updated_by')->references('user_code')->on('users');
            $table->foreign('deleted_by')->references('user_code')->on('users');
        });
        DB::statement('ALTER Table store_type_package_master add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_type_package_master');
    }
}
