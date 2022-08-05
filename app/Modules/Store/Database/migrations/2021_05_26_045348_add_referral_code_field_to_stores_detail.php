<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Doctrine\DBAL\Types\Type;

class AddReferralCodeFieldToStoresDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Type::hasType('double')) {
            Type::addType('double', \Doctrine\DBAL\Types\FloatType::class);
        }
        Schema::table('stores_detail', function (Blueprint $table) {
            $table->string('store_type_package_history_code')->nullable();
            $table->string('referral_code')->nullable();
            $table->foreign('store_type_package_history_code','stphc1')
                ->references('store_type_package_history_code')
                ->on('store_type_package_history');
            $table->string('store_logo')->nullable()->change();
            $table->string('pan_vat_type')->nullable()->change();
            $table->string('pan_vat_no')->nullable()->change();
            $table->string('store_landmark_name')->nullable()->change();
            $table->double('latitude')->nullable()->change();
            $table->double('longitude')->nullable()->change();
            $table->string('store_size_code')->nullable()->change();
            $table->string('store_contact_phone')->nullable()->change();
            $table->string('store_contact_mobile')->nullable()->change();
            $table->string('store_email')->nullable()->change();
            $table->string('store_registration_type_code')->nullable()->change();
            $table->string('store_company_type_code')->nullable()->change();
            $table->string('store_established_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores_detail', function (Blueprint $table) {
            //
        });
    }
}
