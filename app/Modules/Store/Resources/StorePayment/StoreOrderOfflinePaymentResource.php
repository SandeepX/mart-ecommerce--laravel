<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/1/2020
 * Time: 5:20 PM
 */

namespace App\Modules\Store\Resources\StorePayment;


use Illuminate\Http\Resources\Json\JsonResource;

class StoreOrderOfflinePaymentResource extends JsonResource
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
            'store_offline_payment_code'=>$this->store_offline_payment_code,
            'submitted_by'=>$this->submittedBy->name,
            'store_order_code'=>$this->store_order_code,
            'payment_type'=>$this->payment_type,
            'deposited_by'=>$this->deposited_by,
            'purpose'=>$this->purpose,
            'amount'=>number_format($this->amount),
            'voucher_number'=> $this->voucher_number,
            'payment_status' => $this->payment_status,
            'is_verified' => $this->isVerified(),
            'responded_at' => getNepTimeZoneDateTime($this->responded_at),
            'remarks' => $this->remarks ? $this->remarks : '',
            'payment_documents' =>StoreOrderOfflinePaymentDocumentResource::collection($this->paymentDocuments),
            'payment_meta' =>StoreOrderOfflinePaymentMetaResource::collection($this->paymentMetaData),

        ];
    }

}