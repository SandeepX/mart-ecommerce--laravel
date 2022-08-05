<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/20/2020
 * Time: 1:31 PM
 */

namespace App\Modules\Store\Resources\StorePayment;


use Illuminate\Http\Resources\Json\JsonResource;

class StoreMiscellaneousPaymentMinimalResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {


        return [
            'store_misc_payment_code'=>$this->store_misc_payment_code,
            'payment_type'=>$this->payment_type,
            'payment_for' => $this->payment_for,
            'deposited_by'=>$this->deposited_by,
            'amount'=>number_format($this->amount),
            'verification_status' => ucwords($this->verification_status),
            'is_verified' => $this->isVerified(),
            'created_at' => $this->created_at
        ];
    }
}
