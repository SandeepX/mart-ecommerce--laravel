<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlobalNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('global_notification', function (Blueprint $table) {
            $table->string('global_notification_code')->unique('gN');

            $table->primary(['global_notification_code'],'gn_primary');

            $table->text('message');
            $table->string('link')->nullable();
            $table->string('file')->nullable();
            $table->string('created_by');
            $table->enum('created_for',['vendor','store','warehouse','all']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(0);

            $table->timestamps();

            $table->foreign('created_by')->references('user_code')->on('users');

        });

        DB::statement('ALTER Table global_notification add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('global_notification');
    }
}
