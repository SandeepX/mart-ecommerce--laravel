<?php

namespace App\Modules\Product\Services\RelatedProduct;

use App\Modules\Product\Repositories\RelatedProduct\RelatedProductRepository;

class RelatedProductService
{
    private $relatedProductRepository;
    public function __construct(RelatedProductRepository $relatedProductRepository)
    {
        $this->relatedProductRepository = $relatedProductRepository;
    }

    public function relatedProducts($product)
    {
        return $this->relatedProductRepository->relatedProducts($product);
    }
}