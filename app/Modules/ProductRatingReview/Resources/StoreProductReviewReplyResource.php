<?php


namespace App\Modules\ProductRatingReview\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class StoreProductReviewReplyResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'reply_code' => $this->reply_code,
            'review_code' => $this->review_code,
            'reply_message' => $this->reply_message,
            'user_code' => $this->user_code,
            'user_name'=>$this->user->name,
            'created_at' => $this->created_at
        ];
    }
}
