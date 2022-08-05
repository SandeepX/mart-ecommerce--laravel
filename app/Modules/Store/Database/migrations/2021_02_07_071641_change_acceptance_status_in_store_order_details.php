<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAcceptanceStatusInStoreOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("
        ALTER TABLE store_order_details CHANGE acceptance_status acceptance_status ENUM('pending','accepted','rejected') DEFAULT 'accepted'
    ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("
        ALTER TABLE store_order_details CHANGE acceptance_status acceptance_status ENUM('pending','accepted','rejected') DEFAULT 'pending'
    ");
    }
}
