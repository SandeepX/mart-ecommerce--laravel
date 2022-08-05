<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->longText('avatar')->after('password');
            $table->boolean('is_email_verified_at')->after('avatar')->default(0);
            $table->timestamp('email_verified_at')->after('is_email_verified_at')->nullable();
            $table->boolean('is_first_login')->after('email_verified_at')->default(1);
            $table->timestamp('first_login_at')->after('is_first_login')->nullable();
            $table->string('last_login_ip',50)->after('first_login_at');
            $table->timestamp('last_login_at')->after('last_login_ip');
            $table->boolean('is_active')->after('last_login_at')->default(1);

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
            //
        });
    }
}
