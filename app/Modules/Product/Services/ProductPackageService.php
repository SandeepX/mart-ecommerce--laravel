<?php

namespace App\Modules\Product\Services;

use App\Modules\Product\Repositories\ProductPackageRepository;
use App\Modules\Product\Repositories\ProductRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductPackageService
{
    private $productPackageRepository;
    public function __construct(ProductPackageRepository $productPackageRepository)
    {
        $this->productPackageRepository = $productPackageRepository;
    }

    public function storeProductPackageDetail($product, $validatedPackage){
        try{
            $this->productPackageRepository->createProductPackageDetail($product, $validatedPackage);
            
        }catch(Exception $exception){
            throw($exception);
        }
    }

    public function updateProductPackageDetail($product, $validatedPackage){
        try{
            $this->productPackageRepository->updateProductPackageDetail($product, $validatedPackage);
            
        }catch(Exception $exception){
            throw($exception);
        }
    }

}