<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsCountyAndOthersDetailsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('country_code')->nullable()->after('last_login_at');
            $table->string('province_code')->nullable()->after('country_code');
            $table->string('district_code')->nullable()->after('province_code');
            $table->string('municipality_code')->nullable()->after('district_code');
            $table->string('ward_code')->nullable()->after('municipality_code');
            $table->string('street_name')->nullable()->after('ward_code');
            $table->string('citizenship_number_eng')->nullable()->after('street_name');
            $table->string('citizenship_number_nep')->nullable()->after('citizenship_number_eng');
            $table->boolean('has_two_wheeler_license')->default(0)->after('citizenship_number_nep');
            $table->boolean('has_four_wheeler_license')->default(0)->after('has_two_wheeler_license');
            $table->enum('gender',['male','female','others'])->nullable()->after('has_four_wheeler_license');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'country_code',
                'province_code',
                'district_code',
                'municipality_code',
                'ward_code',
                'street_name',
                'citizenship_number_eng',
                'citizenship_number_nep',
                'has_two_wheeler_license',
                'has_four_wheeler_license',
                'gender'
            ]);
        });
    }
}
