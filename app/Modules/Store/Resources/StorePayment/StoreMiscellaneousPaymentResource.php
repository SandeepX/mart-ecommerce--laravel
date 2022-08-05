<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/29/2020
 * Time: 12:13 PM
 */

namespace App\Modules\Store\Resources\StorePayment;


use Illuminate\Http\Resources\Json\JsonResource;

class StoreMiscellaneousPaymentResource extends JsonResource
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
            'submitted_by'=>$this->submittedBy->name,
            'payment_for'=>$this->payment_for,
            'payment_type'=>$this->payment_type,
            'deposited_by'=>$this->deposited_by,
            'purpose'=>$this->when(isset($this->purpose),$this->purpose),
            'transaction_date'=>$this->transaction_date,
            'contact_phone_no'=>$this->contact_phone_no,
            'amount'=>number_format($this->amount),
            'voucher_number'=> $this->when(isset($this->voucher_number),$this->voucher_number),
            'verification_status' => ucwords($this->verification_status),
            'is_verified' => $this->isVerified(),
            'responded_at' => getNepTimeZoneDateTime($this->responded_at),
            'remarks' => $this->remarks ? $this->remarks : '',
            'has_matched' => $this->has_matched ? $this->has_matched : '',
            'payment_documents' =>StoreMiscellaneousPaymentDocumentResource::collection($this->paymentDocuments),
            'payment_meta' =>StoreMiscellaneousPaymentMetaResource::collection($this->paymentMetaData),
        ];
    }
}
