<?php
namespace App\Modules\ProductRatingReview\Helpers;

use App\Modules\ProductRatingReview\Models\StoreProductRating;
use App\Modules\ProductRatingReview\Models\StoreProductReview;

class ProductRatingReviewHelper
{

    public static function filterProductReviews ($filterParams){
        $paginateBy = 5;
        $with =['user','storeProductReviewReplies','storeProductReviewReplies.user'];
        return StoreProductReview::with($with)
            ->where('warehouse_code',$filterParams['warehouse_code'])
            ->where('product_code',$filterParams['product_code'])
            ->when(isset($filterParams['sort_by']),function ($query) use ($filterParams){
                if($filterParams['sort_by'] == 'oldest-first'){
                     $query->orderBy('created_at','ASC');
                }
                if($filterParams['sort_by'] == 'newest-first'){
                     $query->orderBy('created_at','DESC');
                }
            })
            ->latest()
            ->paginate($paginateBy);
    }


    public static function getProductRatingForStore($storeCode,$productCode,$warehouseCode){
        // store based,warehouse based product (average - rating)

            $productRating = StoreProductRating::where('store_code',$storeCode)
                                        ->where('product_code',$productCode)
                                        ->where('warehouse_code',$warehouseCode)
                                        ->avg('rating');

            return manageProductRatingValue($productRating) ;

    }

    public static function getProductRatingForGuest($productCode){
            $productRating = StoreProductRating::where('product_code',$productCode)->avg('rating');
            return manageProductRatingValue($productRating);
        }


}
