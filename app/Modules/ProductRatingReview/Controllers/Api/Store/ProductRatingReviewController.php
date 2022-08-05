<?php


namespace App\Modules\ProductRatingReview\Controllers\Api\Store;


use App\Http\Controllers\Controller;
use App\Modules\ProductRatingReview\Helpers\ProductRatingReviewHelper;
use App\Modules\ProductRatingReview\Models\StoreProductReviewReply;
use App\Modules\ProductRatingReview\Requests\ProductReviewReplyCreateRequest;
use App\Modules\ProductRatingReview\Requests\StoreProductRatingCreateRequest;
use App\Modules\ProductRatingReview\Requests\StoreProductReviewCreateRequest;
use App\Modules\ProductRatingReview\Resources\StoreProductReviewCollection;
use App\Modules\ProductRatingReview\Resources\StoreProductReviewReplyResource;
use App\Modules\ProductRatingReview\Resources\StoreProductReviewResource;
use App\Modules\ProductRatingReview\Services\ProductRatingService;
use App\Modules\ProductRatingReview\Services\ProductReviewService;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use Exception;
use Illuminate\Http\Request;

class ProductRatingReviewController extends Controller
{

    private $storeProductRatingService,$productReviewService;

    public function __construct(ProductRatingService $storeProductRatingService,
                                ProductReviewService $productReviewService){
        $this->storeProductRatingService = $storeProductRatingService;
        $this->productReviewService = $productReviewService;
    }

    public function getWarehouseProductReviewsByStore(Request $request,$productCode){
        try{
            if(auth('api')->check()){
                $warehouseCode = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore(getAuthGuardStoreCode());
                $filterParams = [
                    'product_code' => $productCode,
                    'warehouse_code'=>$warehouseCode,
                    'sort_by'=>$request->get('sort_by')
                ];
                $productReviews = ProductRatingReviewHelper::filterProductReviews($filterParams);
              //  $productReviews=$this->productReviewService->getPaginatedWarehouseProductReviews($warehouseCode,$productCode);
                return new StoreProductReviewCollection($productReviews);
            }
            return sendSuccessResponse('No Reviews Found !',[]);

        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function storeProductRatingByStore(StoreProductRatingCreateRequest $request){
        try{
            $validatedData= $request->validated();
            $this->storeProductRatingService->storeProductRatingByStore($validatedData);
            return sendSuccessResponse('Rating done successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function storeProductReviewByStore(StoreProductReviewCreateRequest $request){
        try{
            $validatedData= $request->validated();
            $productReview =  $this->productReviewService->storeProductReviewByStore($validatedData);
            $productReview = new StoreProductReviewResource($productReview);
            return sendSuccessResponse('Review done successfully',$productReview);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function storeProductReviewReply(ProductReviewReplyCreateRequest $request,$productReviewCode){
        try{
            $validatedData= $request->validated();
            $validatedData['review_code'] = $productReviewCode;
            $productReviewReply = $this->productReviewService->createProductReviewReply($validatedData);
            $productReviewReply = new StoreProductReviewReplyResource($productReviewReply);
            return sendSuccessResponse('Review reply done successfully',$productReviewReply);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function deleteProductReviewByStore($reviewCode){
        try{
            $this->productReviewService->deleteProductReview($reviewCode);
            return sendSuccessResponse('Review deleted successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function deleteProductReviewReply($replyCode){
        try{
            $this->productReviewService->deleteProductReviewReply($replyCode);
            return sendSuccessResponse('Reply deleted successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
