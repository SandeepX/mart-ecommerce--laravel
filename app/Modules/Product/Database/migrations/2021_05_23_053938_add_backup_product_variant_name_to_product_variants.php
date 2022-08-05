<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBackupProductVariantNameToProductVariants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try{
            Schema::table('product_variants', function (Blueprint $table) {
                $table->string('backup_product_variant_name')->nullable()->after('product_variant_name');
            });

        }catch (Exception $exception){
            $this->down();
            throw $exception;
        }

        try{
            DB::beginTransaction();
            $productVariantBackupQuery = "UPDATE product_variants SET backup_product_variant_name = product_variant_name";
            DB::select($productVariantBackupQuery);
            echo  "Product Variant Name Backup Sucessfully "."\n"."";
            DB::commit();

        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('backup_product_variant_name');
        });
    }
}
