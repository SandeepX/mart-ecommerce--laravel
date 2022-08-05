<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailAndPhoneVerifiedColumnsInVendorsDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendors_detail', function (Blueprint $table) {
            $table->timestamp('phone_verified_at')->nullable()->after('contact_fax');
            $table->timestamp('email_verified_at')->nullable()->after('phone_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendors_detail', function (Blueprint $table) {
            $table->dropColumn(['phone_verified_at','email_verified_at']);
        });
    }
}
