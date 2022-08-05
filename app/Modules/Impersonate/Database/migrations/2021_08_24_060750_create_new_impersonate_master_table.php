<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewImpersonateMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('impersonate_master', function (Blueprint $table) {
            $table->string('impersonate_master_code')->primary();
            $table->string('impersonater_code'); //login user code
            $table->string('impersonatee_type');
            $table->string('impersonatee_code',20); //in which logged in user try to impersonate
            $table->string('uuid',2551);
            $table->text('remark')->nullable();
            $table->json('logged_in_details');
            $table->dateTime('logged_in_at');
            $table->dateTime('logged_out_at')->nullable();
            $table->dateTime('expires_at');
            $table->timestamps();
        });
        DB::statement('ALTER Table impersonate_master add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT First');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('impersonate_master');
    }
}
