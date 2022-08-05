<?php

namespace App\Modules\Product\Repositories\RelatedProduct;

class RelatedProductRepository
{
   
    public function relatedProducts($product)
    {
        return $product->category->products()
                    ->where('products_master.product_code', '!=', $product->product_code)
                    ->verified()
                    ->where('products_master.is_active',1)
                    ->inRandomOrder()
                    ->take(8)
                    ->get();
    }
}