<?php

namespace App\Modules\PaymentMethod\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentListResource extends JsonResource
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
            'payment_code' => $this->payment_code,
            'payment_for' => $this->payment_for,
            'verification_status' => $this->verification_status,
            'payment_method' => $this->payment_method,
            'payment_type' => $this->payment_type,
            'created_at' => getReadableDate($this->created_at),
            'amount' => getNumberFormattedAmount($this->amount)
        ];
        return $result;
    }

}
