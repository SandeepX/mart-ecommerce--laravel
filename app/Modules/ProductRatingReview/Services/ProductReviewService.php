<?php


namespace App\Modules\ProductRatingReview\Services;


use App\Modules\ProductRatingReview\Repositories\ProductReviewRepository;
use App\Modules\Store\Helpers\StoreProductHelper;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use Illuminate\Support\Facades\DB;

use Exception;
class ProductReviewService
{

    private $productReviewRepository;

    public function __construct(ProductReviewRepository $productReviewRepository){
        $this->productReviewRepository = $productReviewRepository;
    }

    public function getPaginatedWarehouseProductReviews($warehouseCode,$productCode){
        try{
            $with =['user','storeProductReviewReplies','storeProductReviewReplies.user'];
            $productReviews =$this->productReviewRepository
                                  ->getPaginatedProductReviews($warehouseCode,
                                      $productCode,
                10,
                                      $with);
            return $productReviews;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function storeProductReviewByStore($validatedData){

        try{
            $authStoreCode = getAuthStoreCode();
            $validatedData['store_code'] = $authStoreCode;
            $validatedData['warehouse_code'] = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore($authStoreCode);
            $isProductBoughtByStore = StoreProductHelper::isProductBoughtByStoreFromWarehouse($authStoreCode,
                $validatedData['warehouse_code'],$validatedData['product_code']);
            if (!$isProductBoughtByStore){
                throw new Exception('Only store having the product can review');
            }
            DB::beginTransaction();
            $productReview = $this->productReviewRepository->storeProductReview($validatedData);
            DB::commit();
            return $productReview;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function createProductReviewReply($validatedData){

        try{
            $productReview = $this->productReviewRepository->findOrFailByCode($validatedData['review_code']);
            $authUser = auth()->user();
            if ($authUser->isWarehouseAdminOrUser()){
                $authWarehouseCode = getAuthWarehouseCode();
                if (!$productReview->warehouse_code ==  $authWarehouseCode){
                    throw new Exception('Unauthorised to reply');
                }
            }
            elseif ($authUser->isStoreUser()){
                $authStoreCode = getAuthStoreCode();
                $isProductBoughtByStore = StoreProductHelper::isProductBoughtByStoreFromWarehouse($authStoreCode,
                    $productReview->warehouse_code,$productReview->product_code);
                if (!$isProductBoughtByStore){
                    throw new Exception('Only store having the product can reply');
                }
            }
            else{
                throw new Exception('Only store and warehouse user can reply');
            }
            DB::beginTransaction();
            $productReviewReply = $this->productReviewRepository->createProductReviewReply($validatedData);
            DB::commit();
            return $productReviewReply;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
    public function deleteProductReview($reviewCode){
        try{
            $productReview = $this->productReviewRepository->findOrFailByUserCode($reviewCode,getAuthUserCode());

            DB::beginTransaction();
            $this->productReviewRepository->deleteProductReview($productReview);
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function deleteProductReviewReply($replyCode){
        try{
            $productReviewReply = $this->productReviewRepository->findOrFailReplyByUserCode($replyCode,getAuthUserCode());

            DB::beginTransaction();
            $this->productReviewRepository->deleteProductReviewReply($productReviewReply);
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
}
