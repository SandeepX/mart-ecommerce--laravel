<?php


namespace App\Modules\Product\Helpers;

use App\Modules\Product\Models\ProductCollection;
use App\Modules\Product\Models\ProductMaster;



class ProductCollectionHelper
{

    public static function getUnAddedProductsInCollection($addedProductsInCollectionCodes){
        return ProductMaster::qualifiedToDisplay()->whereNotIn('product_code',$addedProductsInCollectionCodes)->latest()->get();
    }

    public static function getProductsOfCollectionWithPagination($collectionCode,$paginateBy = 8)
    {
        return ProductMaster::whereHas('productCollections',function($query) use ($collectionCode){
            $query->where('product_collection_details.product_collection_code',$collectionCode)
                  ->where('product_collection_details.is_active',1);
        })->qualifiedToDisplay()->paginate($paginateBy);
    }

    public static function getActiveProductCollectionsWithActiveProducts($productsLimit=null){
//        $productCollection= ProductCollection::whereHas('activeProducts')
//        ->with(['products'=>function ($q) use($productsLimit){
////            $q->where('products_master.is_active',1)
//            $q->where('product_collection_details.is_active',1);
//            // ->when($productsLimit, function ($query) use ($productsLimit) {
//            //     return $query->limit($productsLimit);
//            // });
//        }])->active()->latest();
        $productCollections=ProductCollection::
            where('is_active',1)
            ->withCount(['products'=>function($q){
                $q->where('product_collection_details.is_active',1)
                    ->qualifiedToDisplay();
            }])
            ->having('products_count','>',0)
            ->get();
        return $productCollections;

//        return $productCollection->get();
    }

    public static function filterPaginatedProductCollections($filterParameters,$paginateBy,$with=[]){
        $productCollections = ProductCollection::with($with)->withCount($with)
           ->when(isset($filterParameters['collection_title']),function ($query) use($filterParameters){
                $query->where('product_collection_title','like','%'.$filterParameters['collection_title'] . '%');
            });
        $paginateBy = isset($filterParameters['records_per_page'])  ? $filterParameters['records_per_page'] : $paginateBy;

        $productCollections= $productCollections->latest()->paginate($paginateBy);
        return $productCollections;
    }
}
