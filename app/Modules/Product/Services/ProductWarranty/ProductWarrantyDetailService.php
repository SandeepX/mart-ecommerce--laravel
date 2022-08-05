<?php

namespace App\Modules\Product\Services\ProductWarranty;

use App\Modules\Product\Repositories\ProductWarranty\ProductWarrantyDetailRepository;

class ProductWarrantyDetailService
{
    private $productWarrantyDetailRepository;
    public function __construct(ProductWarrantyDetailRepository $productWarrantyDetailRepository)
    {
        $this->productWarrantyDetailRepository = $productWarrantyDetailRepository;
    }

    public function storeProductWarrantyDetail($product, $validatedProductWarrantyDetail){
        $this->productWarrantyDetailRepository->storeProductWarrantyDetail($product, $validatedProductWarrantyDetail);
    }
}