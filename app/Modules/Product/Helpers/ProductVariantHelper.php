<?php


namespace App\Modules\Product\Helpers;

use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Models\ProductVariant;
use App\Modules\Variants\Models\Variant;
use App\Modules\Variants\Models\VariantValue;


class ProductVariantHelper
{
    public static function getPVariantInfoByProduct(ProductMaster $product)
    {

        $productVariantCodes = $product->productVariants->pluck('product_variant_code')->toArray();

        $variantInfo = Variant::select(['variant_name', 'variant_code'])->whereHas('variantValues', function ($query) use ($productVariantCodes) {

            $query->whereIn('variant_value_code', function ($query) use ($productVariantCodes) {
                $query->select('product_variant_details.variant_value_code')
                    ->distinct()
                    ->from('product_variant_details')
                    ->whereIn('product_variant_code', $productVariantCodes);
            });
        })->with(['variantValues' => function ($query) use ($productVariantCodes) {
            $query->whereIn('variant_value_code', function ($query) use ($productVariantCodes) {
                $query->select('product_variant_details.variant_value_code')
                    ->distinct()
                    ->from('product_variant_details')
                    ->whereIn('product_variant_code', $productVariantCodes);
            });
        }])
            ->get();

        return $variantInfo;
    }

    public static function getVariantInfoByProduct(ProductMaster $product)
    {
        $productVariantCodes = $product->productVariants()->orderBy('id', 'asc')->pluck('product_variant_code')->toArray();

        $data = ProductVariant::join('product_variant_details', function ($join) {
                $join->on('product_variant_details.product_variant_code', '=', 'product_variants.product_variant_code')
                    ->where('product_variant_details.deleted_at', null);
            })->join('variant_values', function ($join) {
            $join->on('variant_values.variant_value_code', '=', 'product_variant_details.variant_value_code')
                ->where('variant_values.deleted_at', null);
        })->join('variants', function ($join) {
            $join->on('variants.variant_code', '=', 'variant_values.variant_code')
                ->where('variants.deleted_at', null);
        })
            ->whereIn('product_variants.product_variant_code', $productVariantCodes)
            ->distinct('variants.variant_name')
            ->select([
                'variant_values.variant_code',
                'variant_values.variant_value_name',
                'variant_values.variant_value_code',
                'variant_values.slug as variant_value_slug',
                'variants.*'
            ])->orderBy('product_variant_details.id','asc')->get()->groupBy('variant_name');


      //  dd($data);
   // return $data;
       $formattedData= $data->map(function ($item,$key) {

            return [
                'variant_name' =>$key,
                'variant_code' =>$item[0]['variant_code'],
                'variant_values'=> $item->map(function($item1){
                    return [
                        //'id'=> 4,
                        'variant_code'=> $item1->variant_code,
                        'variant_value_name'=>  $item1->variant_value_name,
                        'variant_value_code'=> $item1->variant_value_code,
                        'slug'=> $item1->variant_value_slug,
                    ];
                }),
            ];
        })->values();

        return $formattedData;
    }
    public static function getMainVariantsInProduct(ProductMaster $product)
    {
        $productVariantCodes = $product->productVariants()->orderBy('id', 'asc')->pluck('product_variant_code')->toArray();

        $data = ProductVariant::join('product_variant_details', function ($join) {
                $join->on('product_variant_details.product_variant_code', '=', 'product_variants.product_variant_code')
                    ->where('product_variant_details.deleted_at', null);
            })->join('variant_values', function ($join) {
            $join->on('variant_values.variant_value_code', '=', 'product_variant_details.variant_value_code')
                ->where('variant_values.deleted_at', null);
        })->join('variants', function ($join) {
            $join->on('variants.variant_code', '=', 'variant_values.variant_code')
                ->where('variants.deleted_at', null);
        })
            ->whereIn('product_variants.product_variant_code', $productVariantCodes)
            ->distinct('variants.variant_name')
            ->select([
                'variant_values.variant_code',
                'variant_values.variant_value_name',
                'variants.variant_name'
              //  'variant_values.variant_value_code',
             //   'variant_values.slug as variant_value_slug',
              //  'variants.*'
            ])->orderBy('product_variant_details.id','asc')->get()->groupBy('variant_name');


       //dd($data);
   // return $data;
       $formattedData= $data->map(function ($item,$key) {

            return [
                'variant_name' =>$key,
                'variant_code' =>$item[0]['variant_code'],
               /* 'variant_values'=> $item->map(function($item1){
                    return [
                        //'id'=> 4,
                        'variant_code'=> $item1->variant_code,
                        'variant_value_name'=>  $item1->variant_value_name,
                        'variant_value_code'=> $item1->variant_value_code,
                        'slug'=> $item1->variant_value_slug,
                    ];
                }),*/
            ];
        })->values();

        return $formattedData;
    }

    public static function checkVariantValueOfSameVariants($combination,$key,$selectedAttributes){

          if(!isset($selectedAttributes[$key]['variant_code'])){
              return false;
          }
          $variantAttributeCode = $selectedAttributes[$key]['variant_code'];
          $variantVaules = VariantValue::where('variant_value_code',$combination['variant_value_code'])->first();
          if($variantVaules->count() > 0 && ($variantAttributeCode !== $variantVaules->variant_code)){
               return false;
          }
        return true;
    }


}
