<?php


namespace App\Modules\ProductRatingReview\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class StoreProductReviewResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'review_code' => $this->review_code,
            'warehouse_code' => $this->warehouse_code,
            'product_code' => $this->product_code,
            'store_code' => $this->store_code,
            'user_code' => $this->user_code,
            'user_name' => $this->user->name,
            'review_message' => $this->review_message,
            'created_at' => $this->created_at,
            'replies' =>StoreProductReviewReplyResource::collection($this->storeProductReviewReplies)
        ];
    }
}
