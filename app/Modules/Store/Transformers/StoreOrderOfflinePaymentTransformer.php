<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/2/2020
 * Time: 10:45 AM
 */

namespace App\Modules\Store\Transformers;


use App\Modules\Store\Models\Payments\StoreOrderOfflinePayment;
use App\Modules\Store\Models\Payments\StoreOrderOfflinePaymentDocument;

class StoreOrderOfflinePaymentTransformer
{

    private $storeOrderOfflinePayment;

    public function __construct(StoreOrderOfflinePayment $storeOrderOfflinePayment)
    {
        $this->storeOrderOfflinePayment = $storeOrderOfflinePayment;
    }

    public function transform(){
        return [

            'store_offline_payment_code'=>$this->storeOrderOfflinePayment->store_offline_payment_code,
            'store_order_code'=>$this->storeOrderOfflinePayment->store_order_code,
            'store_code'=>$this->storeOrderOfflinePayment->store_code,
            'store_name'=>$this->storeOrderOfflinePayment->store->store_name,
            'submitted_by'=>$this->storeOrderOfflinePayment->submittedBy->name,
            'payment_for'=>convertToWords($this->storeOrderOfflinePayment->payment_for,'_'),
            'payment_type'=>$this->storeOrderOfflinePayment->payment_type,
            'deposited_by'=>$this->storeOrderOfflinePayment->deposited_by,
            'purpose'=>$this->storeOrderOfflinePayment->purpose,
            'amount'=>number_format($this->storeOrderOfflinePayment->amount),
            'voucher_number'=> $this->storeOrderOfflinePayment->voucher_number,
            'responded_by' => $this->storeOrderOfflinePayment->respondedBy ? $this->storeOrderOfflinePayment->respondedBy->name : '-',
            'payment_status' => $this->storeOrderOfflinePayment->payment_status,
            'is_verified' => $this->storeOrderOfflinePayment->isVerified(),
            'responded_at' => $this->storeOrderOfflinePayment->responded_at,
            'remarks' => $this->storeOrderOfflinePayment->remarks ? $this->storeOrderOfflinePayment->remarks : '-',
            'payment_meta' =>$this->storeOrderOfflinePayment->paymentMetaData->map(function ($metaDetail){
                return [
                    'key' =>convertToWords($metaDetail->key,'_'),
                    'value' =>$metaDetail->value,

                ];
            }),
            'payment_documents' =>$this->storeOrderOfflinePayment->paymentDocuments->map(function ($document){

                return [
                    'document_type' =>$document->document_type,
                    'file_name' => photoToUrl($document->file_name,asset(StoreOrderOfflinePaymentDocument::UPLOAD_PATH))
                ];
            }),
        ];
    }
}