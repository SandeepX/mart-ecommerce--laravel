<?php
namespace App\Modules\Application\Database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateExtensionToWEBPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//        $productBackUpExtensionQuery = "UPDATE product_images SET image = replace(image,SUBSTRING_INDEX(image,'.',-1),'webp')";
//        DB::select($productBackUpExtensionQuery);
//        echo  "Product Images Extension Change Sucessfully "."\n"."";

        $productVariantExtensionQuery = "UPDATE product_variant_images SET image = replace(image,SUBSTRING_INDEX(image,'.',-1),'webp')";
        DB::select($productVariantExtensionQuery);
        echo  "Product Variant Images Extension Changed Sucessfully "."\n"."";

//
//        $preorderListingsExtensionImageQuery = "UPDATE warehouse_preorder_listings SET banner_image = replace(banner_image,SUBSTRING_INDEX(banner_image,'.',-1),'webp')";
//        DB::select($preorderListingsExtensionImageQuery);
//        echo  "PreOrder Listings Images Extension Changed Sucessfully "."\n"."";
//
//        $productCollectionExtensionImageQuery = "UPDATE product_collections SET product_collection_image = replace(product_collection_image,SUBSTRING_INDEX(product_collection_image,'.',-1),'webp')";
//        DB::select($productCollectionExtensionImageQuery);
//        echo  "Product Collection Images Extension Changed Sucessfully "."\n"."" ;
//
//        $whProductCollectionExtensionImageQuery = "UPDATE wh_product_collections SET product_collection_image = replace(product_collection_image,SUBSTRING_INDEX(product_collection_image,'.',-1),'webp')";
//        DB::select($whProductCollectionExtensionImageQuery);
//        echo  "Warehouse Product Collection Images Extension Changed Sucessfully "."\n"."";


    }
}
