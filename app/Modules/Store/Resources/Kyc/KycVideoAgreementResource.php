<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/22/2020
 * Time: 10:58 AM
 */

namespace App\Modules\Store\Resources\Kyc;


use App\Modules\Store\Models\Kyc\KycAgreementVideo;
use Illuminate\Http\Resources\Json\JsonResource;

class KycVideoAgreementResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return [

            "kyc_agreement_vcode" => $this->kyc_agreement_vcode,
            "submitted_by" => $this->submittedBy->name,
            "agreement_video_for" => convertToWords( $this->agreement_video_for,'_'),
            "agreement_video_name" => photoToUrl($this->agreement_video_name, asset(KycAgreementVideo::VIDEO_UPLOAD_PATH)),
        ];
    }
}