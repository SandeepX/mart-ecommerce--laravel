<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFirmKycMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firm_kyc_master', function (Blueprint $table) {
           
            $table->string('kyc_code')->unique()->primary();
            $table->string('user_code');
            $table->string('store_code');

            $table->string('business_name');
            $table->bigInteger('business_capital');
            $table->enum('business_registered_from',['ward-palika-gharelu','private-public-ltd','partnership']);
          
            $table->string('business_registered_address');
            $table->double('business_address_latitude');
            $table->double('business_address_longitude');

            $table->enum('business_pan_vat_type',['pan','vat']);
            $table->string('business_pan_vat_number'); // pan or vat number

            $table->string('business_registration_no');
            $table->string('business_registered_date');

            $table->string('purpose_of_business');
            $table->integer('share_holders_no');
            $table->string('store_location_ward_no');

            $table->timestamps();


            $table->foreign('user_code')->references('user_code')->on('users');
            $table->foreign('store_code')->references('store_code')->on('stores_detail');
            $table->foreign('store_location_ward_no')->references('location_code')->on('location_hierarchy');
        });

        DB::statement('ALTER Table firm_kyc_master add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('firm_kyc_master');
    }
}
