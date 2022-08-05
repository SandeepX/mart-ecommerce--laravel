<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/29/2020
 * Time: 12:22 PM
 */

namespace App\Modules\Store\Resources\StorePayment;


use App\Modules\Store\Models\Payments\StoreMiscellaneousPaymentDocument;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreMiscellaneousPaymentDocumentResource extends JsonResource
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

            'document_type' =>$this->document_type,
            'file_name' => photoToUrl($this->file_name,asset(StoreMiscellaneousPaymentDocument::UPLOAD_PATH))
        ];
    }
}