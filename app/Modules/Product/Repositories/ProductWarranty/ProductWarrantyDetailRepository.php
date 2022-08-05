<?php


namespace App\Modules\Product\Repositories\ProductWarranty;

class ProductWarrantyDetailRepository
{
    public function storeProductWarrantyDetail($product, $validatedProductWarrantyDetail){
        $product->warrantyDetail()->updateOrCreate(
            ['product_code' => $product->product_code],
            $validatedProductWarrantyDetail
        );
    }
}