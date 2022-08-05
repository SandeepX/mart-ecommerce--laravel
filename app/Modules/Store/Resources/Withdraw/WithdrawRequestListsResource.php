<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/24/2020
 * Time: 4:39 PM
 */

namespace App\Modules\Store\Resources\Withdraw;

use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawRequestListsResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            'withdraw_request_code' => $this->store_balance_withdraw_request_code,
            'requested_amount' => $this->requested_amount,
            'account_no' => $this->account_no,
            'payment_method' => ucwords($this->payment_method),
            'payment_body_name' => ucwords($this->getPaymentBodyName()),
            'status' => $this->status,
            'account_meta' => json_decode($this->account_meta),
            'created_at' => getReadableDate(getNepTimeZoneDateTime($this->created_at),'Y-M-d'),
            'updated_at' => getReadableDate(getNepTimeZoneDateTime($this->updated_at),'Y-M-d'),
        ];
        return $result;
    }

}
