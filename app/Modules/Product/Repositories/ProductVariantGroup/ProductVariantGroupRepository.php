<?php

namespace App\Modules\Product\Repositories\ProductVariantGroup;

use App\Modules\Product\Models\ProductVariant;
use App\Modules\Product\Models\ProductVariantGroup;

class ProductVariantGroupRepository
{
     public function createProductVariantGroup($groupData){
         //dd($groupData);
         $productGroupVariant = ProductVariantGroup::create($groupData);
         return $productGroupVariant;
     }

     public function  createOrUpdateProductVariantGroup($groupData){

         $productGroupVariant = ProductVariantGroup::updateOrcreate(
             [
                 'group_variant_value_code'=>$groupData['group_variant_value_code'],
                 'product_code'=>$groupData['product_code']
             ],
             $groupData
         );
         return $productGroupVariant;
     }

     public function findOrFailByProductVariantGroupCode($productVrainatGroupCode){
        $productVariantGroup = ProductVariantGroup::where('product_variant_group_code',$productVrainatGroupCode)
                                ->latest()
                                ->firstOrFail();
        return $productVariantGroup;

     }

     public function getGroupByProductandGroupCode($productCode,$productVrainatGroupCode){

         $productVariantGroup = ProductVariantGroup::where('product_code',$productCode)
               ->where('product_variant_group_code',$productVrainatGroupCode)
               ->latest()
               ->first();
         return $productVariantGroup;

     }

     public function getProductVariantsByGroupCode($productVariantGroupCode){

         $productVariantofGroup = ProductVariant::where('product_variant_group_code',$productVariantGroupCode)
                                     ->latest()
                                      ->get();
         return $productVariantofGroup;
     }

     public function destroy($productVariantGroup){
      return  $productVariantGroup->delete();
     }

     public function forceDestroy($productVariantGroup){

         return $productVariantGroup->forceDelete();

     }


}
