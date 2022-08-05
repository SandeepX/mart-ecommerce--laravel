<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/29/2020
 * Time: 1:40 PM
 */

namespace App\Modules\Store\Transformers;


use App\Modules\Store\Models\Payments\StoreMiscellaneousPayment;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPaymentDocument;

class StoreMiscellaneousPaymentTransformer
{
    private $storeMiscellaneousPayment;

    public function __construct(StoreMiscellaneousPayment $storeMiscellaneousPayment)
    {
        $this->storeMiscellaneousPayment = $storeMiscellaneousPayment;
    }

    public function transform(){
        return [

            'store_misc_payment_code'=>$this->storeMiscellaneousPayment->store_misc_payment_code,
            'store_code'=>$this->storeMiscellaneousPayment->store_code,
            'store_name'=>$this->storeMiscellaneousPayment->store->store_name,
            //'submitted_by'=>$this->storeMiscellaneousPayment->submittedBy->name,
            'submitted_by'=>($this->storeMiscellaneousPayment->submittedBy)? $this->storeMiscellaneousPayment->submittedBy->name:'N/A',
            'payment_for'=>convertToWords($this->storeMiscellaneousPayment->payment_for,'_'),
            'payment_type'=>$this->storeMiscellaneousPayment->payment_type,
            'deposited_by'=>$this->storeMiscellaneousPayment->deposited_by,
            'transaction_date'=>$this->storeMiscellaneousPayment->transaction_date,
            'contact_phone_no'=>$this->storeMiscellaneousPayment->contact_phone_no,
            'purpose'=>$this->storeMiscellaneousPayment->purpose,
            'amount'=>($this->storeMiscellaneousPayment->amount),
            'voucher_number'=> $this->storeMiscellaneousPayment->voucher_number,
            'responded_by' => $this->storeMiscellaneousPayment->respondedBy ? $this->storeMiscellaneousPayment->respondedBy->name : '-',
            'verification_status' => $this->storeMiscellaneousPayment->verification_status,
            'is_verified' => $this->storeMiscellaneousPayment->isVerified(),
            'responded_at' => $this->storeMiscellaneousPayment->responded_at,
            'remarks' => $this->storeMiscellaneousPayment->remarks ? $this->storeMiscellaneousPayment->remarks : '-',
            'has_matched' => $this->storeMiscellaneousPayment->has_matched ? $this->storeMiscellaneousPayment->has_matched : 0,
            'payment_meta' =>$this->storeMiscellaneousPayment->paymentMetaData->map(function ($metaDetail){
                return [
                    'key' =>convertToWords($metaDetail->key,'_'),
                    'value' =>$metaDetail->value,

                ];
            }),
            'payment_documents' =>$this->storeMiscellaneousPayment->paymentDocuments->map(function ($document){

                return [
                    'document_type' =>$document->document_type,
                    'file_name' => photoToUrl($document->file_name,asset(StoreMiscellaneousPaymentDocument::UPLOAD_PATH))
                ];
            }),
            'questions_checked_meta' => isset($this->storeMiscellaneousPayment->questions_checked_meta) ?  json_decode($this->storeMiscellaneousPayment->questions_checked_meta,true) : []
        ];
    }
}
