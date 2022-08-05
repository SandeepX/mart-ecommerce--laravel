<?php

namespace App\Modules\Product\Services;

use App\Modules\Brand\Repositories\BrandRepository;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Product\Repositories\ProductVariantRepository;
use App\Modules\Vendor\Repositories\VendorRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductService
{
    private $productRepository;
    private $productVariantRepository;
    private $vendorRepository;
    private $brandRepository;
    public function __construct(
        ProductRepository $productRepository,
        ProductVariantRepository $productVariantRepository,
        VendorRepository $vendorRepository,
        BrandRepository $brandRepository

    ) {
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->vendorRepository = $vendorRepository;
        $this->brandRepository=$brandRepository;
    }

    public function getAllProducts()
    {
        return $this->productRepository->getAllProducts();
    }


    public function getVerifiedProductsCount()
    {
        return $this->productRepository->getAllVerifiedProductsCount();
    }

    public function getAllVerifiedProducts($select=[])
    {
        return $this->productRepository->select($select)->getAllVerifiedProducts();
    }

    public function getActiveVerifiedProducts()
    {
        return $this->productRepository->getActiveVerifiedProducts();
    }

    public function filterProductByVendor($filterBy)
    {
        if ($filterBy == 'all' || $filterBy == '')
            $products = $this->getAllProducts();
        else
            $products = $this->getProductsByVendor($filterBy);

        return $products;
    }

    public function getProductsByVendor($vendorCode)
    {
        $vendor = $this->vendorRepository->findOrFailVendorByCode($vendorCode);
        return $this->productRepository->getProductsByVendor($vendor);
    }
    public function getProductsByBrandSlug($brandSlug,$paginated){
        $brand=$this->brandRepository->findOrFailBrandBySlug($brandSlug);
        return $this->productRepository->getProductsByBrand($brand,$paginated);
    }

    public function findProductByCode($productCode)
    {
        return $this->productRepository->findProductByCode($productCode);
    }


    public function findOrFailProductByCode($productCode)
    {
        return $this->productRepository->findOrFailProductByCode($productCode);
    }

    public function findOrFailProductByCodeWith($productCode,array $with)
    {
        return $this->productRepository->findOrFailProductByCodeWith($productCode,$with);
    }

    public function findProductBySlug($productSlug)
    {
        return $this->productRepository->findOrFailProductBySlug($productSlug);
    }

    public function findVerifiedProductBySlug($productSlug)
    {
        return $this->productRepository->findOrFailVerifiedProductBySlug($productSlug);
    }

    public function findVerifiedProductByCode($productCode)
    {
        return $this->productRepository->findOrFailVerifiedProductByCode($productCode);
    }

    public function findOrFailProductBySlug($productSlug)
    {
        return $this->productRepository->findOrFailProductBySlug($productSlug);
    }

    public function findProductBySlugWith($productSlug, array $with)
    {
        return $this->productRepository->findOrFailProductBySlugWith($productSlug, $with);
    }

    public function findOrFailProductBySlugWith($productSlug, array $with)
    {
        return $this->productRepository->findOrFailProductBySlugWith($productSlug, $with);
    }


    public function findOrFailVerifiedProductBySlugWith($productSlug, array $with,$select='*')
    {
        return $this->productRepository->findOrFailVerifiedProductBySlugWith($productSlug,$with,$select='*');
    }

    public function storeProduct($validated)
    {
        try {

            $product = $this->productRepository->createProduct($validated);
            return $product;
        } catch (Exception $exception) {
            throw ($exception);
        }
    }

    public function updateProduct($product, $validated)
    {
        try {

            $product = $this->productRepository->updateProduct($product, $validated);
            return $product;
        } catch (Exception $exception) {
            throw ($exception);
        }
    }

    public function updateActiveStatus($code){

        try{
            $product = $this->productRepository->findOrFailProductByCode($code);
            DB::beginTransaction();
            $product->is_active == 1?$data['is_active'] =0 :$data['is_active']=1;
            $this->productRepository->updateActiveStatus($data,$product);
            DB::commit();
            return $product;
        }catch (Exception $exception){
            DB::rollBack();
            throw  $exception;
        }
    }

    // public function deleteProduct($productCode)
    // {
    //     try {
    //         $product = $this->productRepository->findOrFailProductByCode($productCode);
    //         if ($product->hasVariants()) {
    //             //Delete Product Variant details
    //             $this->productVariantRepository->deleteProductVariantsByProduct($product);
    //         }

    //         return $this->productRepository->deleteProduct($product);
    //     } catch (Exception $exception) {
    //         throw ($exception);
    //     }
    // }

    public function deleteProduct($product)
    {
        try {

            if ($product->hasVariants()) {
                //Delete Product Variant details
                $this->productVariantRepository->deleteProductVariantsByProduct($product);
            }

            return $this->productRepository->deleteProduct($product);
        } catch (Exception $exception) {
            throw ($exception);
        }
    }
}
