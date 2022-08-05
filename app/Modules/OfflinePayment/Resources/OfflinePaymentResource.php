<?php

namespace App\Modules\OfflinePayment\Resources;

use App\Modules\OfflinePayment\Models\OfflinePaymentDoc;
use Illuminate\Http\Resources\Json\JsonResource;

class OfflinePaymentResource extends JsonResource
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
            'payment_code' => $this->offline_payment_code,
            'payment_for' => $this->payment_for,
            'payment_type' => $this->payment_type,
            'payment_holder_type'=>$this->payment_holder_type,
            'payment_holder_code'=>$this->offline_payment_holder_code,
            'deposited_by' => $this->deposited_by,
            'transaction_date' => $this->transaction_date,
            'contact_phone_no' => $this->contact_phone_no,
            'amount' => getNumberFormattedAmount($this->amount),
            'verification_status' => $this->verification_status,
            'created_by' => $this->submittedBy->name,
            'created_at' => getReadableDate($this->created_at),
            'is_verified' => $this->isVerified(),
            'payment_meta_data' => $this->paymentMetaData->map(function ($metaDetail) {
                return [
                    'key' => convertToWords($metaDetail->key, '_'),
                    'value' => $metaDetail->value,
                ];
            }),
            'payment_documents' => $this->paymentDocuments->map(function ($document) {
                return [
                    'document_type' => $document->document_type,
                    'file_name' => photoToUrl($document->file_name, asset(OfflinePaymentDoc::UPLOAD_PATH))
                ];
            }),
        ];
        return $result;
    }
}
