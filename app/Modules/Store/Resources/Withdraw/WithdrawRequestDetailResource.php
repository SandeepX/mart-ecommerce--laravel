<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/24/2020
 * Time: 4:39 PM
 */

namespace App\Modules\Store\Resources\Withdraw;

use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawRequestDetailResource extends JsonResource
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
            'reason' => $this->reason,
            'remarks' => $this->remarks,
            'completion_estimation_date' => $this->completion_estimation_date,
            'priority' => $this->priority,
            'payment_body_code' => $this->payment_body_code,
            'verified_at' => $this->verified_at ? getReadableDate(getNepTimeZoneDateTime($this->verified_at)) : null,
            'created_at' => getReadableDate(getNepTimeZoneDateTime($this->created_at)),
        ];
        return $result;
    }

}
