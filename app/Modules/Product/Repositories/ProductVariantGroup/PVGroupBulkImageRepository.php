<?php

namespace App\Modules\Product\Repositories\ProductVariantGroup;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Product\Models\PVGroupBulkImage;
use Exception;

class PVGroupBulkImageRepository
{
     use ImageService;
     public function saveGroupBulkImages($validatedImages,$product_variant_group_code){
         $filesInsertedInBulImages = [];
             foreach($validatedImages as $image){
                 $data['product_variant_group_code'] = $product_variant_group_code;
                 $data['image'] = $this->storeImageInServer($image, PVGroupBulkImage::IMAGE_PATH);
                 array_push(
                     $filesInsertedInBulImages,
                            [
                                'path'=>PVGroupBulkImage::IMAGE_PATH,
                                'image' => $data['image']
                            ]
                 );
                 PVGroupBulkImage::create($data);
             }

             return $filesInsertedInBulImages;
     }

     public function findOrFaillByGroupBulkImageCode($groupBulkImageCode){
         return PVGroupBulkImage::where('pv_group_bulk_image_code',$groupBulkImageCode)
             ->firstOrFail();
     }

     public function getGroupBulkImagesByGroupCode($productVariantGroupCode){
          $groupBulkImages = PVGroupBulkImage::where('product_variant_group_code',$productVariantGroupCode)
                                             ->latest()
                                             ->get();
          return $groupBulkImages;
     }

     public function destroy($pvGroupBulkImage){

                  $this->deleteImageFromServer(
                      PVGroupBulkImage::IMAGE_PATH,
                      $pvGroupBulkImage->image
                  );

         $pvGroupBulkImage->forceDelete();
     }

     public function findGroupBulkImageByImageCode($groupBulkImageCode){
         return PVGroupBulkImage::where('pv_group_bulk_image_code',$groupBulkImageCode)
                    ->firstOrFail();
     }

}
