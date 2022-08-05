<?php

namespace App\Modules\Vendor\Observers;

use App\Modules\Vendor\Models\ProductPriceList;

class ProductPriceObserver
{
    public function creating(ProductPriceList $productPriceList)
    {
        $authUserCode = getAuthUserCode();
        $productPriceList->product_price_list_code = $productPriceList->generateCode();
        $productPriceList->created_by = $authUserCode;
        $productPriceList->updated_by = $authUserCode;
    }

    public function updating(ProductPriceList $productPriceList){
        $productPriceList->updated_by = getAuthUserCode();
    }

    public function deleting(ProductPriceList $productPriceList){
        $productPriceList->deleted_by = getAuthUserCode();
        $productPriceList->save();
    }
}

