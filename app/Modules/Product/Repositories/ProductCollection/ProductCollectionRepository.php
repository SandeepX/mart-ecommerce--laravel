<?php


namespace App\Modules\Product\Repositories\ProductCollection;

use App\Modules\AlpasalWarehouse\Models\WarehouseProductCollection;
use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Product\Models\ProductCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProductCollectionRepository
{
    use ImageService;

    private $productCollection;

    public function __construct(ProductCollection $productCollection)
    {
        $this->productCollection = $productCollection;
    }

    public function getAllProductCollections()
    {
        return $this->productCollection->withCount('products')->latest()->get();
    }

    public function getActiveProductCollections()
    {
        return $this->productCollection->withCount('products')->active()->latest()->get();
    }

    /*public function getActiveProductCollections()
    {
        return WarehouseProductApiCollection::with(['products'=>function ($q){
             $q->where('products_master.is_active',1);
        }])->active()->latest()->get();
    }*/

    // public function getActiveProductCollectionsWithProducts()
    // {
    //     return $this->productCollection
    //                 ->active()
    //                 ->with(['products'])
    //                 ->withCount('products')
    //                 ->latest()
    //                 ->get();
    // }

    public function findProductCollectionByCode($productCollectionCode)
    {
        return $this->productCollection->where('product_collection_code', $productCollectionCode)->first();
    }

    public function findProductCollectionBySlug($productCollectionSlug)
    {
        return $this->productCollection->where('product_collection_slug', $productCollectionSlug)->first();
    }

    public function findOrFailProductCollectionByCode($productCollectionCode)
    {
        if (!$productCollection = $this->findProductCollectionByCode($productCollectionCode)) {
            throw new ModelNotFoundException('No Such Product Collection Found !');
        }
        return $productCollection;
    }

    public function findOrFailProductCollectionBySlug($productCollectionSlug)
    {
        if (!$productCollection = $this->findProductCollectionBySlug($productCollectionSlug)) {
            throw new ModelNotFoundException('No Such Product Collection Found !');
        }
        return $productCollection;
    }

    public function createProductCollection($validated)
    {
        $validated['product_collection_image'] = $this->storeImageInServer($validated['product_collection_image'], $this->productCollection->uploadFolder);
        $validated['product_collection_slug'] = make_slug($validated['product_collection_title']);
        $validated['is_active'] = 1;
        return $this->productCollection->create($validated);
    }

    public function updateProductCollection($validated, $productCollection)
    {

        if (isset($validated['product_collection_image'])) {
            $this->deleteImageFromServer($this->productCollection->uploadFolder, $productCollection->product_collection_image);
            $validated['product_collection_image'] = $this->storeImageInServer($validated['product_collection_image'],$this->productCollection->uploadFolder);
        }

        $validated['product_collection_slug'] = make_slug($validated['product_collection_title']);
        $productCollection->update($validated);
        return $productCollection->fresh();
    }

    public function deleteProductCollection($productCollection)
    {
        $productCollection->delete();
        return $productCollection;
    }



    /*  ------------ Adding Products to Collection -----------------*/



    public function getProductsInCollection($productCollection)
    {
//       return $productCollection->products;
        return $productCollection->products()
            ->groupBy('product_collection_details.product_code','product_collection_details.product_collection_code')
          #  ->where('products_master.is_active',1)
          ->qualifiedToDisplay()
            ->get();
    }


    public function getActiveProductsInCollection($productCollection)
    {
       return $productCollection->products()->wherePivot('is_active',1)->get();
    }



    public function addProductsToCollection($productCollection,array $productCodes,$productsInCollection)
    {
        $productCodeInCollection=[];
        foreach($productsInCollection as $productInCollection)
        {
            $productCodeInCollection[]=$productInCollection->product_code;
        }
        $result = !empty(array_intersect($productCodeInCollection, $productCodes));
        if($result)
        {
            throw new \Exception('product already added in collection');
        }
        $authUserCode = getAuthUserCode();
        $data = [];
        foreach ($productCodes as $productCode) {
            $data[$productCode] = [
                'is_active' => 1,
                'created_by' => $authUserCode,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

        }

        $productCollection->products()->syncWithoutDetaching($data);

       return $productCollection;
    }


    public function removeProductsFromCollection($productCollection,$productCode)
    {

       DB::table('product_collection_details')
       ->where('product_collection_code',$productCollection->product_collection_code)
       ->where('product_code',$productCode)
       ->delete();
    }
    public function updateProductCollectionActiveStatus($validated,ProductCollection $productCollection){

        $authUserCode = getAuthUserCode();
        // $validated['updated_by'] = $authUserCode;
        $productCollection->updated_by=$authUserCode;
        $productCollection->is_active=$validated['is_active'];
        $productCollection->save();
        return $productCollection;
    }
    public function findProductByCode($productCollectionCode,$productCode){
        $ProductCollection=ProductCollection:: join('product_collection_details', function ($join) use ($productCollectionCode) {
            $join->on('product_collection_details.product_collection_code', '=', 'product_collections.product_collection_code')
                ->where('product_collection_details.product_collection_code', '=',$productCollectionCode);
        })
            ->join('products_master', function ($join) use ($productCode) {
                $join->on('products_master.product_code', '=', 'product_collection_details.product_code')
                    ->where('product_collection_details.product_code', '=',$productCode);
            })
            ->select('product_collections.product_collection_code','products_master.product_code','product_collection_details.is_active')
            ->first();
        return $ProductCollection;
    }
    public function updateActiveStatus($validated,$productCollectionCode, $productCode){

        DB::table('product_collection_details')
            ->where('product_collection_code',$productCollectionCode)
            ->where('product_code',$productCode)
            ->update(['is_active' => $validated['is_active']]);
    }
}
