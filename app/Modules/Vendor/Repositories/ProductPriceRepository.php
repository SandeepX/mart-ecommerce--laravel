<?php

namespace App\Modules\Vendor\Repositories;

use App\Modules\Product\Helpers\ProductPriceHelper;
use App\Modules\Product\Models\ProductPriceList;

use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Product\Repositories\ProductVariantRepository;
use App\Modules\Variants\Repositories\VariantRepository;
use Exception;

class ProductPriceRepository
{

    public function getProductPrice($product)
    {
        return $product->priceList;
    }

    public function storeProductPrice($validatedProductPrice, $productCode)
    {
        foreach(array_filter($validatedProductPrice['mrp']) as $key => $mrp){

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

    public function forceDeleteProductPriceList($product){
        $product->priceList()->forceDelete();
    }

}
