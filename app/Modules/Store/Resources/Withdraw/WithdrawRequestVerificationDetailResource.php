<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/24/2020
 * Time: 4:39 PM
 */

namespace App\Modules\Store\Resources\Withdraw;

use App\Modules\Store\Models\Balance\StoreBalanceWithdrawRequestVerificationDetail;
use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawRequestVerificationDetailResource extends JsonResource
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
            'withdraw_request_verification_details_code' => $this->withdraw_request_verification_details_code,
            'withdraw_request_code' => $this->store_balance_withdraw_request_code,
            'amount' => $this->amount,
            'payment_verification_source' => $this->payment_verification_source,
            'payment_method' => ucwords($this->payment_method),
            'payment_body_name' => ucwords($this->getPaymentBodyName()),
            'status' => $this->status,
            'payment_meta' => json_decode($this->payment_meta),
            'reason' => $this->reason,
            'remarks' => $this->remarks,
            'payment_body_code' => $this->payment_body_code,
            'proof' => photoToUrl($this->proof,url(StoreBalanceWithdrawRequestVerificationDetail::DOCUMENT_PATH)),
//            'verified_at' => getReadableDate(getNepTimeZoneDateTime($this->verified_at)),
            'created_at' => getReadableDate(getNepTimeZoneDateTime($this->created_at),'Y-M-d'),
        ];
        return $result;
    }

}
