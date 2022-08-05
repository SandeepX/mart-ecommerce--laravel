<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/16/2020
 * Time: 4:16 PM
 */

namespace App\Modules\Store\Resources\Kyc;

use App\Modules\Location\Repositories\LocationHierarchyRepository;
use App\Modules\Location\Services\LocationHierarchyService;
use Illuminate\Http\Resources\Json\JsonResource;

class IndividualKycResource extends JsonResource
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
          //  'user_code'=>$this->user_code,
            'store_code'=>$this->store_code,
            'kyc_for'=>$this->kyc_for,
            'name_in_devanagari'=>$this->name_in_devanagari,
            'name_in_english'=>$this->name_in_english,
            'marital_status'=>$this->marital_status,
            'marital_status_display_as'=>ucfirst($this->marital_status),
//            'gender'=> $this->gender,
//            'gender_display_as'=>config('kyc_information_transformation.gender')[$this->gender],
            'pan_no'=>$this->pan_no,
            'educational_qualification'=>$this->educational_qualification,
            'educational_qualification_display_as'=>ucfirst($this->educational_qualification),
            'permanent_house_no'=>$this->permanent_house_no,
            'permanent_street'=>$this->permanent_street,
            'permanent_location_tree'=>(new LocationHierarchyService((new LocationHierarchyRepository)))->getLocationPath($this->permanent_ward_no),
            'present_house_no'=>$this->present_house_no,
            'present_street'=>$this->present_street,
            'present_location_tree'=>(new LocationHierarchyService((new LocationHierarchyRepository)))->getLocationPath($this->present_ward_no),
//            'landmark'=>$this->landmark,
//            'latitude'=>$this->latitude,
//            'longitude'=>$this->longitude,
            'landlord_name'=>$this->landlord_name,
            'landlord_contact_no'=>$this->landlord_contact_no,
            'verification_status' => $this->verification_status,
            'is_verified' => $this->isVerified(),
            'responded_at' => getNepTimeZoneDateTime($this->responded_at),
            'remarks' => $this->remarks ? $this->remarks : '',
//            'kyc_family_detail' =>new IndividualKycFamilyDetailResource($this->kycFamilyDetail),
            'kyc_citizenship_detail'=>new IndividualKycCitizenshipDetailResource($this->kycCitizenshipDetail),
            'kyc_banks_detail'=>($this->kycBanksDetail)? KycBankResource::collection($this->kycBanksDetail):'N/A',
            'kyc_documents' =>KycDocumentResource::collection($this->kycDocuments),
            'can_update_kyc' => $this->can_update_kyc
        ];
    }
}
