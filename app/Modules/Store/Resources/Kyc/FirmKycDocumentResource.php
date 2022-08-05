<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/16/2020
 * Time: 3:04 PM
 */

namespace App\Modules\Store\Resources\Kyc;

use App\Modules\Store\Models\Kyc\FirmKycDocument;
use Illuminate\Http\Resources\Json\JsonResource;

class FirmKycDocumentResource extends JsonResource
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

            //'kyc_document_code' =>$this->kyc_document_code,
            //'kyc_code' =>$this->kyc_code,
            'document_type' =>$this->document_type,
            'document_file' => photoToUrl($this->document_file,asset(FirmKycDocument::DOCUMENT_PATH))
        ];
    }
}