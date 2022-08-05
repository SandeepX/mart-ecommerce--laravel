<?php


namespace App\Modules\Product\Repositories;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Product\Models\ProductVariant;
use App\Modules\Product\Models\ProductVariantDetail;
use App\Modules\Product\Models\ProductVariantImage;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductVariantRepository
{
    use ImageService;
    private $productVariant;
    private $productVariantDetail;
    public function __construct(ProductVariant $productVariant, ProductVariantDetail $productVariantDetail)
    {
        $this->productVariant = $productVariant;
        $this->productVariantDetail = $productVariantDetail;
    }

    public function findProductVariantByVariantCode($variantCode){
        return ProductVariant::findOrFail($variantCode);
    }

    public function findProductVareiantByProductAndVariantCode($productCode,$variantCode){

        return ProductVariant::where('product_code',$productCode)
                              ->where('product_variant_code',$variantCode)
                              ->first();

    }

    public function findOrFailByProductCodeAndVariantCode($productCode,$variantCode){

        $productVariant = ProductVariant::where('product_code',$productCode)->where('product_variant_code',$variantCode)->first();

        if (!$productVariant){
            throw new ModelNotFoundException('No Such Product Variant for this product');
        }

        return $productVariant;
    }

    public function findOrFailByProductCodeAndName($productCode,$variantName){

        $productVariant = ProductVariant::where('product_code',$productCode)->where('product_variant_name',$variantName)->first();

        if (!$productVariant){
            throw new ModelNotFoundException('No Such Product Variant for this product');
        }

        return $productVariant;
    }

    public function createProductVariant($product, $variantTotal){
        $authUserCode = getAuthUserCode();
        foreach($variantTotal as $variantData){

            $validated['product_code'] = $product->product_code;
            $validated['product_variant_code'] = $this->productVariant->generateProductVariantCode();
            $validated['product_variant_name'] = $variantData['combination_name'];
            $validated['created_by'] = $authUserCode;
            $validated['updated_by'] = $authUserCode;
            // $validated['price'] = $variantData['price'];
            // $validated['remarks'] = $variantData['remarks'];
            //Insert Into Product Variant Table
            $productVariant = ProductVariant::create($validated);

            //Insert Into Product Variant Images
            if(isset($variantData['images'])){
                foreach($variantData['images'] as $image){
                    $data['product_code'] = $product->product_code;
                    $data['image'] = $this->storeImageInServer($image, 'uploads/products/variants');
                    $data['product_variant_code'] = $productVariant->product_variant_code;
                    ProductVariantImage::create($data);
                }
            }
            foreach($variantData['values'] as $variantValue){
                $productVariantDetailCode = $this->productVariantDetail->generateProductVariantDetailCode();
                ProductVariantDetail::create([
                    'product_variant_detail_code' => $productVariantDetailCode,
                    'product_variant_code' => $productVariant->product_variant_code,
                    'variant_value_code' => $variantValue,
                    'created_by' => $authUserCode,
                    'updated_by' => $authUserCode
                ]);
            }
        }

    }

    public function newCreateProductVariant($product, $variantTotal,$productVariantGroupCode){
        $authUserCode = getAuthUserCode();
        $filesInsertedInProductVariantImages = [];
        foreach($variantTotal as $variantData){

            $validated['product_code'] = $product->product_code;
            $validated['product_variant_group_code'] = $productVariantGroupCode;
            $validated['product_variant_name'] = $variantData['combination_name'];
            $validated['product_vv_code'] = $variantData['product_vv_code'];
            $validated['created_by'] = $authUserCode;
            $validated['updated_by'] = $authUserCode;
            // $validated['price'] = $variantData['price'];
            // $validated['remarks'] = $variantData['remarks'];
            //Insert Into Product Variant Table
            $productVariant = ProductVariant::create($validated);

            //Insert Into Product Variant Images
            if(isset($variantData['images'])){
                foreach($variantData['images'] as $image){
                    $data['product_code'] = $product->product_code;
                    $data['image'] = $this->storeImageInServer($image, 'uploads/products/variants');
                    $data['product_variant_code'] = $productVariant->product_variant_code;
                    array_push(
                        $filesInsertedInProductVariantImages,
                        [
                            'path' => ProductVariantImage::IMAGE_PATH,
                            'image'=>  $data['image']
                        ]);
                    ProductVariantImage::create($data);
                }
            }
            foreach($variantData['values'] as $variantValue){
                $productVariantDetailCode = $this->productVariantDetail->generateProductVariantDetailCode();
                ProductVariantDetail::create([
                    'product_variant_detail_code' => $productVariantDetailCode,
                    'product_variant_code' => $productVariant->product_variant_code,
                    'variant_value_code' => $variantValue,
                    'created_by' => $authUserCode,
                    'updated_by' => $authUserCode
                ]);
            }
        }

       return $filesInsertedInProductVariantImages;

    }


    public function updateProductVariant($product, $variantTotal){
        $authUserCode = getAuthUserCode();
        foreach($variantTotal as $variantData){


            $validated['product_code'] = $product->product_code;
            $validated['product_variant_name'] = $variantData['combination_name'];
            $validated['created_by'] = $authUserCode;
            $validated['updated_by'] = $authUserCode;
            // $validated['price'] = $variantData['price'];
            // $validated['remarks'] = $variantData['remarks'];
            $productVariant = ProductVariant::updateOrcreate([
                'product_code' => $product->product_code,
                'product_variant_name' => $variantData['combination_name'],
            ],
                $validated
            );

            $imageCount = $productVariant->images()->count();
            $requestImageCount = (isset($variantData['images'])) ? count($variantData['images']) : 0;
            if($imageCount+$requestImageCount > 3){
                throw new Exception('Image Should be less than 3', 422);
            }

            //Insert Into Product Variant Images
            if(isset($variantData['images'])){
                foreach($variantData['images'] as $image){
                    $data['product_code'] = $product->product_code;
                    $data['image'] = $this->storeImageInServer($image, 'uploads/products/variants');
                    $data['product_variant_code'] = $productVariant->product_variant_code;
                    ProductVariantImage::create($data);
                }
            }

            foreach($variantData['values'] as $variantValue){
                ProductVariantDetail::updateOrcreate([
                    'product_variant_code' => $productVariant->product_variant_code,
                    'variant_value_code' => $variantValue
                    ],
                    [
                    'created_by' => $authUserCode,
                    'updated_by' => $authUserCode
                    ]
                );
            }
        }
    }

    public function newUpdateProductVariant($product, $variantTotal,$productVariantGroupCode){
        $authUserCode = getAuthUserCode();
        $filesInsertedInProductVariantImages = [];

        foreach($variantTotal as $variantData){

            $validated['product_code'] = $product->product_code;
            $validated['product_variant_group_code'] = $productVariantGroupCode;
            $validated['product_vv_code'] = $variantData['product_vv_code'];
            $validated['product_variant_name'] = $variantData['combination_name'];
            $validated['created_by'] = $authUserCode;
            $validated['updated_by'] = $authUserCode;
            // $validated['price'] = $variantData['price'];
            // $validated['remarks'] = $variantData['remarks'];
            $productVariant = ProductVariant::updateOrcreate([
                //'product_variant_code' => isset($variantData['product_variant_code']) ? $variantData['product_variant_code'] : '',
                'product_code' => $product->product_code,
                'product_vv_code' => $variantData['product_vv_code']
//                'product_variant_group_code' => $productVariantGroupCode
            ],
                $validated
            );

            $imageCount = $productVariant->images()->count();
            $requestImageCount = (isset($variantData['images'])) ? count($variantData['images']) : 0;
            if($imageCount+$requestImageCount > 3){
                throw new Exception('Image Should be less than 3', 422);
            }

            //Insert Into Product Variant Images
            if(isset($variantData['images'])){
                foreach($variantData['images'] as $image){
                    $data['product_code'] = $product->product_code;
                    $data['image'] = $this->storeImageInServer($image, 'uploads/products/variants');
                    $data['product_variant_code'] = $productVariant->product_variant_code;

                    array_push(
                        $filesInsertedInProductVariantImages,
                        [
                        'path' => ProductVariantImage::IMAGE_PATH,
                        'image'=>  $data['image']
                        ]);
                    ProductVariantImage::create($data);
                }
            }

            foreach($variantData['values'] as $variantValue){
                ProductVariantDetail::updateOrcreate([
                    'product_variant_code' => $productVariant->product_variant_code,
                    'variant_value_code' => $variantValue
                ],
                    [
                        'created_by' => $authUserCode,
                        'updated_by' => $authUserCode
                    ]
                );
            }
        }

        return $filesInsertedInProductVariantImages;
    }

    public function deleteProductVariantsByProduct($product){
        $variants = $product->productVariants;

        //check if variant exists in undeletable relations
        foreach($variants as $variant){
            $this->checkVariantBeforeDelete($variant);
        }



        foreach($variants as $variant){

            $checkDelete = $variant->canDelete('carts', 'storeOrderDetails');
            if(!$checkDelete['can']){
                throw new Exception('Sorry You cannot Edit the product variant as it is in  active '. $checkDelete['relation']);
            }

            $variant->details()->delete();
             //Delete Image From Server
            $this->deleteImageFromServerByVariant($variant);
            $variant->images()->delete();
            $variant->price()->delete();
            $variant->delete();
            $variant->deleted_by = getAuthUserCode();
            $variant->save();
        }
    }

    public function forceDeleteProductVariantsByProduct($product){
        $variants = $product->productVariants;


        //check if variant exists in undeletable relations
        foreach($variants as $variant){
            $this->checkVariantBeforeDelete($variant);
        }

        foreach($variants as $variant){
            $variant->details()->forceDelete();
            $variant->price()->forceDelete();
            //Delete Image From Server
            $this->deleteImageFromServerByVariant($variant);
            $variant->images()->forceDelete();
            $variant->forceDelete();
        }
    }

    public function deleteProductVariantImageBycode($imageCode){
        $image = ProductVariantImage::findOrFail($imageCode);
        $image->delete();
    }

    public function forceDeleteProductVariantImageBycode($productCode,$productVariantCode,$imageCode){
      #  $productVariant = $this->findOrFailByProductCodeAndVariantCode($productCode,$productVariantCode);
        $image = ProductVariantImage::where('product_code',$productCode)
                                     ->where('product_variant_code',$productVariantCode)
                                     ->where('product_variant_image_code',$imageCode)
                                     ->firstOrFail();
        $this->deleteImageFromServer('uploads/products/variants/', $image->image);
        $image->forceDelete();

    }

    public function deleteProductVariantByVariant($variant){

        //check if variant exists in undeletable relations
        $this->checkVariantBeforeDelete($variant);

        $variant->details()->delete();
        $variant->images()->delete();
        $variant->price()->delete();
        $variant->delete();
    }

    public function forceDeleteProductVariantByVariant($variant){

        //check if variant exists in undeletable relations
        $this->checkVariantBeforeDelete($variant);

        $variant->details()->forceDelete();
        //Delete Image From Server
        $this->deleteImageFromServerByVariant($variant);
        $variant->images()->forceDelete();
        $variant->price()->forceDelete();
        $variant->forceDelete();
    }

    public function deleteProductVariantsByVariantValueCode($productCode, $variantValueCode){
        $productVariants = ProductVariant::whereHas('details', function($query) use($variantValueCode){
            $query->where('variant_value_code', $variantValueCode);
        })->whereHas('product', function($query) use($productCode){
            $query->where('product_code', $productCode);
        })->get();

        foreach($productVariants as $productVariant){
            $this->deleteProductVariantByVariant($productVariant);
        }
    }

    public function forceDeleteProductVariantsByVariantValueCode($productCode, $variantValueCode){
        $productVariants = ProductVariant::whereHas('details', function($query) use($variantValueCode){
            $query->where('variant_value_code', $variantValueCode);
        })->where('product_code',$productCode)->get();

        foreach($productVariants as $productVariant){
            $this->forceDeleteProductVariantByVariant($productVariant);
        }
    }

    public function deleteImageFromServerByVariant($variant){
        foreach($variant->images as $image){
            $this->deleteImageFromServer('uploads/products/variants/', $image->image);
        }
    }

    public function checkVariantBeforeDelete($variant){

        //check if variant exists in carts ans store orders
        $checkDelete = $variant->canDelete(
            'unitPackagingDetail',
            'carts',
            'storeOrderDetails',
            'warehouseProducts',
            'warehousePreOrderProducts',
            'warehousePurchaseOrderDetails'
        );
            if(!$checkDelete['can']){
                throw new Exception('Sorry You cannot perform the action since product variant is in active '. $checkDelete['relation']);
            }
    }

    public function getProductVariantByProductCode($productCode,$select)
    {
        return ProductVariant::select($select)->where('product_code',$productCode)->get();
    }

}
