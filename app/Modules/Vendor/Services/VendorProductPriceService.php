<?php

namespace App\Modules\Vendor\Services;

use App\Modules\Product\Helpers\ProductPriceHelper;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Product\Repositories\ProductVariantRepository;
use App\Modules\Vendor\Repositories\ProductPriceRepository;

use Exception;

class VendorProductPriceService
{
    private $productPriceRepository;
    public function __construct(
        ProductPriceRepository $productPriceRepository
    )
    {
        $this->productPriceRepository = $productPriceRepository;
    }

    public function getProductPrice($product)
    {
        return $this->productPriceRepository->getProductPrice($product);
    }

    public function storeProductPrice($validatedProductPrice, $productCode)
    {
        $this->checkVendorProduct($productCode);

        return $this->productPriceRepository->storeProductPrice($validatedProductPrice, $productCode);
    }


    public function checkVendorProduct($productCode){
        $vendor = auth()->user()->vendor;
        $productCodes = $vendor->products()->pluck('product_code')->toArray();
        if(!in_array($productCode, $productCodes)){
            throw new Exception('The Selected Product is Invalid', 400);
        }
    }
}
