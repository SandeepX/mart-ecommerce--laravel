<?php


namespace App\Modules\Product\Repositories;

use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\Product\Helpers\ProductHelper;
use App\Modules\Product\Models\ProductMaster;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductRepository extends RepositoryAbstract
{
    private $productMaster;

    public function __construct(ProductMaster $productMaster)
    {
       $this->productMaster = $productMaster;
    }

    public function getAllProducts(){
        return ProductMaster::latest()->get();
    }


    public function getAllVerifiedProductsCount()
    {
        return ProductMaster::verified()->count();
    }


    public function getAllVerifiedProducts()
    {
        return ProductMaster::select($this->select)->verified()->latest()->get();
    }

    public function getActiveVerifiedProducts()
    {
        return ProductHelper::getActiveVerifiedProducts();
    }

    public function getProductsByVendor($vendor){
        return $vendor->products()->latest()->get();
    }
    public function  getProductsByBrand($brand,$paginated){
        return $brand->products()->latest()->paginate($paginated);
    }

    public function findProductByCode($productCode){
        return ProductMaster::where('product_code', $productCode)->first();
    }

    public function findOrFailProductByCode($productCode){

        if(!$product = $this->findProductByCode($productCode)){
            throw new ModelNotFoundException('No Such Product Found');
        }
        return $product;
    }
    public function findOrFailProductByCodeWith($productCode,array $with){

        $product = ProductMaster::with($with)->where('product_code',$productCode)->first();

        if(!$product){
            throw new ModelNotFoundException('No Such Product Found');
        }
        return $product;
    }


    public function findProductBySlug($productSlug){
        return ProductMaster::where('slug', $productSlug)->first();
    }

    public function findOrFailProductBySlug($productSlug){

        if(!$product = $this->findProductBySlug($productSlug)){
            throw new ModelNotFoundException('No Such Product Found');
        }
        return $product;
    }

    public function findOrFailVerifiedProductBySlug($productSlug){

        $product = ProductMaster::qualifiedToDisplay()->where('slug', $productSlug)->first();

        if(!$product){
            throw new ModelNotFoundException('No Such Product Found');
        }

        return $product;

        // if($checkProduct->hasVariants()){
        //     foreach($checkProduct->productVariants as $variant){
        //         if(!$variant->isVerified()){
        //             throw new ModelNotFoundException('No Such Product Found');
        //         }
        //     }
        //     $product = $checkProduct;
        // }else{
        //     $product = ProductMaster::where('slug', $productSlug)->verified()->first();
        // }

        // if(!$product){
        //     throw new ModelNotFoundException('No Such Product Found');
      //  }
        //return $product;
    }

    public function findOrFailVerifiedProductByCode($productCode){
        $product = ProductMaster::where('product_code', $productCode)->qualifiedToDisplay()->first();
        if(!$product){
            throw new ModelNotFoundException('No Such Product Found');
        }
        return $product;
    }

    public function findOrFailProductBySlugWith($productSlug,array $with){

        $product = ProductMaster::with($with)->where('slug',$productSlug)->first();

        if(!$product){
            throw new ModelNotFoundException('No Such Product Found');
        }
        return $product;
    }

    public function findOrFailVerifiedProductBySlugWith($productSlug,array $with,$select='*'){

        $product = ProductMaster::with($with)->select($select)->where('slug',$productSlug)->qualifiedToDisplay()->first();

        if(!$product){
            throw new ModelNotFoundException('No Such Product Found');
        }
        return $product;
    }

    public function createProduct($validated){
        $authUserCode = getAuthUserCode();
        $validated['product_code'] = $this->productMaster->generateProductCode();
        $validated['slug'] = makeSlugWithHash($validated['product_name']);
        $validated['sku'] = $this->productMaster->generateSkuCode();
        $validated['highlights'] = json_encode($validated['highlights']);
        $validated['video_link'] = $this->extractIdFromVideo($validated['video_link']);
        $validated['vendor_code'] = auth()->user()->vendor->vendor_code;
        $validated['created_by'] = $authUserCode;
        $validated['updated_by'] = $authUserCode;
        $product =  ProductMaster::create($validated)->fresh();
        $product->slug = make_slug($product->product_name).'-'.$product->id;
        $product->save();
        return $product;
    }

    public function updateProduct($product, $validated){
        $authUserCode = getAuthUserCode();
        $validated['highlights'] = json_encode($validated['highlights']);
        $validated['video_link'] = $this->extractIdFromVideo($validated['video_link']);
        $validated['slug'] = make_slug($validated['product_name']).'-'.$product->id;
        $validated['updated_by'] = $authUserCode;
        $product->update($validated);
        return $product->fresh();
    }

    public function deleteProduct($product)
    {
        $this->checkProductBeforeDelete($product);

        $product->images()->delete();
        $product->package()->delete();
        $product->priceList()->delete();
        $product->delete();

        $product->deleted_by = getAuthUserCode();
        $product->save();
        return $product;
    }

    public function checkProductBeforeDelete($product){
        //check if variant exists in carts ans store orders
        $checkDelete = $product->canDelete(
            'unitPackagingDetails',
            'productCollections',
            'carts',
            'storeOrderDetails',
            'warehouseProducts',
            'warehousePreOrderProducts',
            'warehousePurchaseOrderDetails'
        );
            if(!$checkDelete['can']){
                throw new Exception('Sorry Cannot perform the action since this product  is in active '. $checkDelete['relation']);
            }
    }

    public function extractIdFromVideo($video){
        $youtubeId = '';
        if(isset($video)){
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video, $match)) {
                $youtubeId = $match[1];
            }
        }
        return $youtubeId;
    }

    public function updateActiveStatus($validated,ProductMaster $product){

        $authUserCode = getAuthUserCode();
       // $validated['updated_by'] = $authUserCode;
        $product->updated_by=$authUserCode;
        $product->is_active=$validated['is_active'];
        $product->save();
        return $product;
    }
}
