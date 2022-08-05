<?php


namespace App\Modules\ProductRatingReview\Repositories;

use App\Modules\ProductRatingReview\Models\StoreProductReview;
use App\Modules\ProductRatingReview\Models\StoreProductReviewReply;
use App\Modules\Store\Models\Store;

class ProductReviewRepository
{

    public function getPaginatedProductReviews($warehouseCode,$productCode,$paginateBy=10,$with=[]){

        return StoreProductReview::with($with)->where('warehouse_code',$warehouseCode)
            ->where('product_code',$productCode)->latest()->paginate($paginateBy);
    }

    public function findOrFailByCode($reviewCode,$with=[]){
        return StoreProductReview::with($with)->where('review_code',$reviewCode)->firstOrFail();
    }

    public function findOrFailByUserCode($reviewCode,$userCode,$with=[]){
        return StoreProductReview::with($with)->where('review_code',$reviewCode)
            ->where('user_code',$userCode)->firstOrFail();
    }

    public function findOrFailReplyByUserCode($replyCode,$userCode,$with=[]){
        return StoreProductReviewReply::with($with)
            ->where('reply_code',$replyCode)->where('user_code',$userCode)->firstOrFail();
    }

    public function storeProductReview($validatedData){
        $validatedData['user_code'] = getAuthUserCode();
        return StoreProductReview::create($validatedData);
//        return StoreProductReview::create([
//            'store_code'=>$validatedData['store_code'],
//            'warehouse_code' =>$validatedData['warehouse_code'],
//            'product_code' =>$validatedData['product_code'],
//        ],$validatedData)->fresh();
    }

    public function createProductReviewReply($validatedData){

        return StoreProductReviewReply::create($validatedData)->fresh();
    }

    public function deleteProductReview(StoreProductReview $storeProductReview){

       //StoreProductReviewReply::where('review_code',$storeProductReview->review_code)->delete();//wont trigger deleting event
        StoreProductReviewReply::where('review_code',$storeProductReview->review_code)->get()->each(function($reply) {
            $reply->delete();
        });
        $storeProductReview->delete();
    }

    public function deleteProductReviewReply(StoreProductReviewReply $storeProductReviewReply){

        $storeProductReviewReply->delete();
    }
}
