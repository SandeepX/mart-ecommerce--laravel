<?php
/**
 * Created by PhpStorm.
 * User: Sandeep
 * Date: 08/15/2021
 * Time: 1:40 PM
 */

namespace App\Modules\OfflinePayment\Transformers;

use App\Modules\OfflinePayment\Helpers\OfflinePaymentHelper;
use App\Modules\OfflinePayment\Models\OfflinePaymentDoc;
use App\Modules\OfflinePayment\Models\OfflinePaymentMaster;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPayment;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPaymentDocument;

class OfflinePaymentTransformer
{
    private $offlinePayment;

    public function __construct(OfflinePaymentMaster $offlinePayment)
    {
        $this->offlinePayment = $offlinePayment;
    }

    public function transform()
    {

        return [
            'offline_payment_code' => $this->offlinePayment->offline_payment_code,
            'payment_holder_type' => isset($this->offlinePayment->payment_holder_type) ? $this->offlinePayment->payment_holder_type : 'N/A',
            'offline_payment_holder_code' => isset($this->offlinePayment->offline_payment_holder_code) ? $this->offlinePayment->offline_payment_holder_code : 'N/A',
            'name' =>  OfflinePaymentHelper::getOfflinePaymentHolderName($this->offlinePayment),
            'submitted_by' => ($this->offlinePayment->submittedBy) ? $this->offlinePayment->submittedBy->name : 'N/A',
            'payment_for' => convertToWords($this->offlinePayment->payment_for, '_'),
            'payment_type' => $this->offlinePayment->payment_type,
            'deposited_by' => $this->offlinePayment->deposited_by,
            'transaction_date' => $this->offlinePayment->transaction_date,
            'contact_phone_no' => $this->offlinePayment->contact_phone_no,
            'purpose' => $this->offlinePayment->purpose,
            'amount' => ($this->offlinePayment->amount),
            'voucher_number' => $this->offlinePayment->voucher_number,
            'responded_by' => $this->offlinePayment->respondedBy ? $this->offlinePayment->respondedBy->name : '-',
            'verification_status' => $this->offlinePayment->verification_status,
            'is_verified' => $this->offlinePayment->isVerified(),
            'responded_at' => $this->offlinePayment->responded_at,
            'remarks' => $this->offlinePayment->remarks ? $this->offlinePayment->remarks : '-',
            'payment_meta' => $this->offlinePayment->paymentMetaData->map(function ($metaDetail) {
                return [
                    'key' => convertToWords($metaDetail->key, '_'),
                    'value' => $metaDetail->value,

                ];
            }),
            'payment_documents' => $this->offlinePayment->paymentDocuments->map(function ($document) {
                return [
                    'document_type' => $document->document_type,
                    'file_name' => photoToUrl($document->file_name, asset(OfflinePaymentDoc::UPLOAD_PATH))
                ];
            }),
        ];
    }
}

