<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/24/2020
 * Time: 4:39 PM
 */

namespace App\Modules\LuckyDraw\Resources;

use App\Modules\Location\Repositories\LocationHierarchyRepository;
use App\Modules\LuckyDraw\Models\StoreLuckydraw;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreLuckydrawDetailResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

            $eligibleMessage = "The store should be approved and the Store Should have Sales Dispatch more than or equal to $this->eligibility_sales_amount past  $this->days Days ";

            $result = [
                'store_luckydraw_code' => $this->store_luckydraw_code,
                'luckydraw_name' => $this->luckydraw_name,
                'type' => $this->type,
                'prize' => $this->prize,
                'eligibility_sales_amount' => $this->eligibility_sales_amount,
                'eiligibility' => $eligibleMessage,
                'days' => $this->days,
                'status' => $this->status,
                'remarks' => $this->remarks,
                'opening_time' => $this->opening_time,
                'readable_opening_time'=> getReadableDate($this->opening_time),
                'winner_pickup_time' => $this->pickup_time,
                'terms_and_conditions' => json_decode($this->terms),
                'luckdraw_image' => photoToUrl($this->image,url(StoreLuckydraw::IMAGE_PATH))
               // 'store_logo' => photoToUrl($this->storeLuckydrawWinner->store->store_logo, asset('uploads/stores/logos'))
            ];

        return $result;
    }

}
