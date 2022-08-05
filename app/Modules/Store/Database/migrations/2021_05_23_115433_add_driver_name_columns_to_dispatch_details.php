<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddDriverNameColumnsToDispatchDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       try{
//
           Schema::table('store_order_dispatch_details', function (Blueprint $table) {
               $table->string('driver_name')->nullable()->after('vehicle_name');
           });
//
           Schema::table('store_pre_order_dispatch_details', function (Blueprint $table) {
               $table->string('driver_name')->nullable()->after('vehicle_name');
           });
//
       }catch (Exception $exception){
           $this->down();
           throw $exception;
       }
//
       try{
//
           DB::beginTransaction();
//
           $productBackUpImageQuery = "UPDATE store_pre_order_dispatch_details SET driver_name = vehicle_name";
           DB::select($productBackUpImageQuery);
           echo  "Store Pre Order Driver name populated Sucessfully "."\n"."";
//
           $productBackUpImageQuery = "UPDATE store_order_dispatch_details SET driver_name = vehicle_name";
           DB::select($productBackUpImageQuery);
           echo  "Store Order Driver name populated Sucessfully "."\n"."";
//
           DB::commit();
//
       }catch (Exception $exception){
           DB::rollBack();
           throw $exception;
       }
//
//
//
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_order_dispatch_details', function (Blueprint $table) {
            $table->dropColumn('driver_name');
        });
        Schema::table('store_pre_order_dispatch_details', function (Blueprint $table) {
            $table->dropColumn('driver_name');
        });
    }
}
