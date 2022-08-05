<?php


namespace App\Modules\Product\Utilities;


interface IProductPackagingPriceCalculator
{
     function calculatePrice($productPackagingDetail);
}
