<?php
namespace App\Modules\Application\Database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CopyImagesToBackupImages extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $productBackUpImageQuery = "UPDATE product_images SET backup_image = image";
        DB::select($productBackUpImageQuery);
        echo  "Product Images Backup Sucessfully "."\n"."";

        $productVariantBackUpImageQuery = "UPDATE product_variant_images SET backup_image = image";
        DB::select($productVariantBackUpImageQuery);
        echo  "Product Variant Images Backup Sucessfully "."\n"."";

        $preorderListingsBackUpImageQuery = "UPDATE warehouse_preorder_listings SET backup_image = banner_image";
        DB::select($preorderListingsBackUpImageQuery);
        echo  "PreOrder Listings Images Backup Sucessfully "."\n"."";

        $productCollectionBackUpImageQuery = "UPDATE product_collections SET backup_image = product_collection_image";
        DB::select($productCollectionBackUpImageQuery);
        echo  "Product Collection Images Backup Sucessfully "."\n"."" ;

        $whProductCollectionBackUpImageQuery = "UPDATE wh_product_collections SET backup_image = product_collection_image";
        DB::select($whProductCollectionBackUpImageQuery);
        echo  "Warehouse Product Collection Images Backup Sucessfully "."\n"."";


    }
}
