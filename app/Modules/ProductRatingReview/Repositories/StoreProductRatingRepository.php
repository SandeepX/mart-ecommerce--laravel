<?php


namespace App\Modules\ProductRatingReview\Repositories;


use App\Modules\ProductRatingReview\Models\StoreProductRating;

class StoreProductRatingRepository
{

    public function storeProductRating($validatedData){

        $validatedData['user_code'] = getAuthUserCode();
        return StoreProductRating::updateOrCreate([
            'store_code'=>$validatedData['store_code'],
            'warehouse_code' =>$validatedData['warehouse_code'],
            'product_code' =>$validatedData['product_code'],
        ],$validatedData)->fresh();
    }
}
