<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/1/2020
 * Time: 5:24 PM
 */

namespace App\Modules\Store\Resources\StorePayment;


use App\Modules\Store\Models\Payments\StoreOrderOfflinePaymentDocument;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreOrderOfflinePaymentDocumentResource extends JsonResource
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
            'document_code' => $this->payment_doc_code,
            'document_type' =>$this->document_type,
            'file_name' => photoToUrl($this->file_name,asset(StoreOrderOfflinePaymentDocument::UPLOAD_PATH))
        ];
    }
}