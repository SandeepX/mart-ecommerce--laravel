<?php

namespace App\Modules\PaymentGateway\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OnlinePaymentResource extends JsonResource
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
            'payment_code' => $this->online_payment_master_code,
            'payment_for' => $this->transaction_type,
            'payment_type' => $this->digitalWallet->wallet_name,
            'payment_holder_type'=>strtolower(substr($this->payment_initiator, (strrpos($this->payment_initiator,'\\') + 1))),
            'payment_holder_code'=>$this->initiator_code,
            'transaction_date' => getReadableDate($this->created_at),
            'transaction_id'=> $this->transaction_id,
            'status' => $this->status,
            'deposited_by' => $this->submittedBy->name,
            'amount' => getNumberFormattedAmount(convertPaisaToRs($this->amount)),
            'is_verified' => $this->isVerified(),
            'payment_meta_data' => $this->paymentMetaData->map(function ($metaDetail) {
                return [
                    'key' => convertToWords($metaDetail->key, '_'),
                    'value' => $metaDetail->value,
                ];
            })
        ];
        return $result;
    }


}
