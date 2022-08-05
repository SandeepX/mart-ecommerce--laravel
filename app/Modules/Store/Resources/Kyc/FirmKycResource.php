<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/16/2020
 * Time: 2:36 PM
 */

namespace App\Modules\Store\Resources\Kyc;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Modules\Location\Repositories\LocationHierarchyRepository;
use App\Modules\Location\Services\LocationHierarchyService;

class FirmKycResource extends JsonResource
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
            'kyc_code'=>$this->kyc_code,
           // 'user_code'=>$this->user_code,
            'store_code'=>$this->store_code,
            'business_name'=>$this->business_name,
            'business_capital'=>$this->business_capital,
            'business_registered_from'=>$this->business_registered_from,
            'business_registered_address'=>$this->business_registered_address,
            'business_address_latitude'=>$this->business_address_latitude,
            'business_address_longitude'=>$this->business_address_longitude,
            'business_pan_vat_type'=>$this->business_pan_vat_type,
            'business_pan_vat_number'=>$this->business_pan_vat_number,
            'business_registration_no'=>$this->business_registration_no,
            'business_registered_date'=>$this->business_registered_date,
            'purpose_of_business'=>$this->purpose_of_business,
            'share_holders_no'=>$this->share_holders_no,
            'store_location_tree' => (new LocationHierarchyService((new LocationHierarchyRepository)))->getLocationPath($this->store_location_ward_no),
            'verification_status' => $this->verification_status,
            'is_verified' => $this->isVerified(),
            'responded_at' =>  getNepTimeZoneDateTime($this->responded_at),
            'remarks' => $this->remarks ? $this->remarks : '',
            'kyc_banks_detail'=>KycBankResource::collection($this->kycBanksDetail),
            'kyc_documents' =>FirmKycDocumentResource::collection($this->kycDocuments),
            'can_update_kyc' => $this->can_update_kyc
        ];
    }
}