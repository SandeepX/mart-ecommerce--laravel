<?php

use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableIndividualKycMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('individual_kyc_master', function (Blueprint $table) {
           //not able to make gender field to nullable through this migratio, plz make it nullable directly in database.
            if (!Type::hasType('double')) {
                Type::addType('double', FloatType::class);
            }
            Schema::table('individual_kyc_master', function (Blueprint $table) {
                $table->string('landmark')->nullable()->change();
                $table->double('latitude')->nullable()->change();
                $table->double('longitude')->nullable()->change();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Type::hasType('double')) {
            Type::addType('double', FloatType::class);
        }
        Schema::table('individual_kyc_master', function (Blueprint $table) {
            $table->string('landmark')->nullable(false)->change();
            $table->double('latitude')->nullable(false)->change();
            $table->double('longitude')->nullable(false)->change();
        });
    }
}
