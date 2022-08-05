<?php

namespace App\Modules\Product\Repositories;

use App\Modules\Product\Models\ProductPriceList;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductPriceRepository
{
    public function storeProductPrice($validatedProductPrice, $productCode)
    {
        foreach($validatedProductPrice['mrp'] as $key => $mrp){
            ProductPriceList::updateOrCreate([
                'product_code' => $productCode,
                'product_variant_code' => $validatedProductPrice['product_variant_code'][$key],
            ],
            [
                'mrp' => $mrp,
                'admin_margin_type' => $validatedProductPrice['admin_margin_type'][$key],
                'admin_margin_value' => $validatedProductPrice['admin_margin_value'][$key] ,
                'wholesale_margin_type' => $validatedProductPrice['wholesale_margin_type'][$key],
                'wholesale_margin_value' => $validatedProductPrice['wholesale_margin_value'][$key],
                'retail_store_margin_type' => $validatedProductPrice['retail_store_margin_type'][$key],
                'retail_store_margin_value' => $validatedProductPrice['retail_store_margin_value'][$key],
            ]
        );
        }
    }

    public function getPriceNotHavingVariants($productCode){

        $price = ProductPriceList::where('product_code',$productCode)->where('product_variant_code',null)->first();

        if (!$price){

            throw new ModelNotFoundException('Price not found for the product');
        }

        return $price;
    }

    public function getPriceHavingVariants($productCode,array $variantsCode){

        $prices = ProductPriceList::where('product_code',$productCode)->whereIn('product_variant_code',$variantsCode)->get();

        return $prices;
    }

    public function findOrFailByProductCodeAndVariantCode($productCode,$variantCode){

        $price= ProductPriceList::where('product_code',$productCode)->where('product_variant_code',$variantCode)->first();

        if (!$price){
            throw new ModelNotFoundException('Price not found for product code and variant');
        }

        return $price;
    }
}