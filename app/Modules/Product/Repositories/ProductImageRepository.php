<?php

namespace App\Modules\Product\Repositories;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Product\Models\ProductImage;
use App\Modules\Product\Models\ProductVariantImage;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductImageRepository
{
    use ImageService;
    private $productImage;
    public function __construct(ProductImage $productImage)
    {
        $this->productImage = $productImage;
    }

    public function findProductImageByCode($productImageCode){
        $productImage = ProductImage::where('product_image_code', $productImageCode)->first();
        if($productImage){
            return $productImage;
        }
        throw new ModelNotFoundException('No Such Product Image Found!');
    }

    public function findOrFailProductImageByCode($productImageCode){
        return $this->findProductImageByCode($productImageCode);
    }

    public function getProductFeaturedImagesByProductCode($productCode){
        return ProductImage::where('product_code',$productCode)
                            ->latest()
                           ->get();
    }

    public function getImagesOfProductCodeAndVariantCode($productCode,$variantCode){

        return ProductVariantImage::where('product_code',$productCode)->where('product_variant_code',$variantCode)->get();
    }

    public function storeProductImages($product, $validatedImage){
        //handle image
        foreach($validatedImage['images'] as $image){
            $data['product_image_code'] = $this->productImage->generateProductImageCode();
            $data['image'] = $this->storeImageInServer($image, 'uploads/products');
            $product->images()->create($data);
        }
    }

    public function updateProductImages($product, $validatedImage){
        //handle image
       // dd($validatedImage);
        foreach($validatedImage as  $key => $image){
            //dd($image);
            $data['product_image_code'] = $this->productImage->generateProductImageCode();
            $data['image'] = $this->storeImageInServer($image, 'uploads/products');

            $product->images()->create($data);
        }
    }

    public function destroy($productImage){
        $productImage->delete();
    }

    public function forceDestroy($productImage){
        //Delete Image form server
        $this->deleteImageFromServer('uploads/products/', $productImage->image);
        $productImage->forceDelete();
    }
}
