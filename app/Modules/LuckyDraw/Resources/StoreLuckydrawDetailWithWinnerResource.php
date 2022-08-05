<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/24/2020
 * Time: 4:39 PM
 */

namespace App\Modules\LuckyDraw\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreLuckydrawDetailWithWinnerResource extends JsonResource
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
        $luckydrawWinners = $this->storeLuckydrawWinners;

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
            // 'store_logo' => photoToUrl($this->storeLuckydrawWinner->store->store_logo, asset('uploads/stores/logos'))
        ];

        if($luckydrawWinners->count() > 0){
            foreach($luckydrawWinners as $key=>$luckydrawWinner)
            {
                $luckydrawWinnerStore = $luckydrawWinner->store;
                $result['lucky_draw_winners'][$key] = [
                    'store_name' => $luckydrawWinnerStore->store_name,
                    'store_code' => $luckydrawWinnerStore->store_code,
                    'winner_eligibility' => $luckydrawWinner->winner_eligibility,
                    'store_full_location'=> $luckydrawWinnerStore->store_full_location
                ] ;
            }

        }


        return $result;
    }

}
