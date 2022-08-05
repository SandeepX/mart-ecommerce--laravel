<?php


namespace App\Modules\ProductRatingReview\Services;

use App\Modules\ProductRatingReview\Repositories\StoreProductRatingRepository;
use App\Modules\Store\Helpers\StoreProductHelper;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use Exception;
use Illuminate\Support\Facades\DB;
class ProductRatingService
{
    private $storeProductRatingRepository;

    public function __construct(StoreProductRatingRepository $storeProductRatingRepository){
        $this->storeProductRatingRepository = $storeProductRatingRepository;
    }

    public function storeProductRatingByStore($validatedData){

        try{
            $authStoreCode = getAuthStoreCode();
            $validatedData['warehouse_code'] = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore($authStoreCode);
            $validatedData['store_code'] = $authStoreCode;
            $isProductBoughtByStore = StoreProductHelper::isProductBoughtByStoreFromWarehouse($authStoreCode,
                $validatedData['warehouse_code'],$validatedData['product_code']);
            if (!$isProductBoughtByStore){
                throw new Exception('Only store having the product can rate');
            }
            DB::beginTransaction();
            $this->storeProductRatingRepository->storeProductRating($validatedData);
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
}
