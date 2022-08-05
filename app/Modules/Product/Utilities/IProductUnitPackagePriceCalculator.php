<?php


namespace App\Modules\Product\Utilities;


class IProductUnitPackagePriceCalculator implements IProductPackagingPriceCalculator
{
    function calculatePrice($productPackagingDetail)
    {
        return 2;
    }
}
