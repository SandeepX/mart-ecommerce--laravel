<?php

namespace App\Modules\Product\Services;

use App\Modules\Product\Models\ProductVariantImage;
use App\Modules\Product\Repositories\ProductImageRepository;

class ProductImageService
{
    private $productImageRepository;
    public function __construct(ProductImageRepository $productImageRepository)
    {
        $this->productImageRepository = $productImageRepository;
    }

    public function concatImagePath($variantImages){

        $images=[];
        foreach ($variantImages as $variantImage){

            array_push($images, url(ProductVariantImage::IMAGE_PATH.$variantImage));
        }

        return $images;
    }

    public function getProductFeaturedImagesByProductCode($productCode){
      return $this->productImageRepository->getProductFeaturedImagesByProductCode($productCode);
    }

    public function getImageListOfProductCodeAndVariantCode($productCode,$variantCode){

        return $this->productImageRepository->getImagesOfProductCodeAndVariantCode($productCode,$variantCode)->pluck('image')->toArray();
    }

    public function storeProductImages($product, $validatedImage){
        $this->productImageRepository->storeProductImages($product, $validatedImage);
    }

    public function updateProductImages($product, $validatedImage){
        if(isset($validatedImage['images'])){
            $this->productImageRepository->updateProductImages($product, $validatedImage['images']);
        }
    }

    public function deleteProductImageBycode($productImageCode){
        $productImage = $this->productImageRepository->findOrFailProductImageByCode($productImageCode);
        $this->productImageRepository->destroy($productImage);
    }

    public function forceDeleteProductImageBycode($productImageCode){
        $productImage = $this->productImageRepository->findOrFailProductImageByCode($productImageCode);
        $this->productImageRepository->forceDestroy($productImage);
    }
}
