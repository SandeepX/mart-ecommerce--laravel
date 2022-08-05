<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactDetailsColumnInWarehouses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouses', function (Blueprint $table) {

            $table->enum('pan_vat_type', ['pan','vat'])->default('pan')->after('remarks');
            $table->string('pan_vat_no')->after('pan_vat_type')->nullable();
            $table->string('warehouse_logo')->after('pan_vat_no');
            $table->string('contact_name')->after('warehouse_logo')->nullable();
            $table->string('contact_email')->after('contact_name')->nullable();
            $table->string('contact_phone_1')->after('contact_email')->nullable();
            $table->string('contact_phone_2')->after('contact_phone_1')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropColumn([
                'pan_vat_type','pan_vat_no','warehouse_logo','contact_name','contact_email','contact_phone_1','contact_phone_2'
            ]);

        });
    }
}
