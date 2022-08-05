<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhoneAndEmailVerifiedAtInManagersDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('managers_detail', function (Blueprint $table) {
            $table->timestamp('email_verified_at')->nullable()->after('status_responded_at');
            $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('managers_detail', function (Blueprint $table) {
            $table->dropColumn(['email_verified_at','phone_verified_at']);
        });
    }
}
