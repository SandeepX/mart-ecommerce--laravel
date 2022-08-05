<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsInGeneralSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->string('primary_bank_name')->nullable()->after('secondary_contact');
            $table->string('primary_bank_account_number')->nullable()->after('primary_bank_name');
            $table->string('primary_bank_branch')->nullable()->after('primary_bank_account_number');
            $table->string('secondary_bank_name')->nullable()->after('primary_bank_branch');
            $table->string('secondary_bank_account_number')->nullable()->after('secondary_bank_name');
            $table->string('secondary_bank_branch')->nullable()->after('secondary_bank_account_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            //
        });
    }
}
