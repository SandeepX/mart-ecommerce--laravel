<?php

namespace App\Modules\Product\Services\ProductVerification;

use App\Modules\Product\Repositories\ProductVerification\ProductVerificationRepository;

class ProductVerificationService
{
    private $productVerificationRepository;
    public function __construct(ProductVerificationRepository $productVerificationRepository)
    {
        $this->productVerificationRepository = $productVerificationRepository;
    }

    public function storeProductVerification($validatedProductVerification, $product)
    {
        $this->productVerificationRepository->storeProductVerification($validatedProductVerification, $product);
    }
}