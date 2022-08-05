<?php

namespace App\Modules\Product\Repositories;

use App\Modules\Product\Models\ProductMaster;

class ProductCategoryRepository
{
    public function syncProductCategories($product, array $categoryCodes){
        $product->categories()->sync($categoryCodes);
    }

    
}