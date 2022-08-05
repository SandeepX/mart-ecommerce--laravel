<?php


namespace App\Modules\Product\Repositories;

use App\Modules\Product\Models\ProductPackageDetail;

class ProductPackageRepository
{
    public function createProductPackageDetail($product, $validated){
        $authUserCode = getAuthUserCode();
        $validated['created_by'] = $authUserCode;
        $validated['updated_by'] = $authUserCode;
        $product->package()->create($validated);
    }

    public function updateProductPackageDetail($product, $validated){
        $authUserCode = getAuthUserCode();
        $validated['updated_by'] = $authUserCode;
        $product->package()->update($validated);
    }
}