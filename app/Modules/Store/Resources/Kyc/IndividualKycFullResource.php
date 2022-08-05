<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/19/2020
 * Time: 3:14 PM
 */

namespace App\Modules\Store\Resources\Kyc;


use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Location\Repositories\LocationHierarchyRepository;
use App\Modules\Location\Services\LocationHierarchyService;

class IndividualKycFullResource extends JsonResource
{


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //expired ...kept just for reference
        return[];

        return [
            'kyc_code'=>$this->kyc_code,
            'user_code'=>$this->user_code,
            'submitted_by' => $this->submittedBy->name,
            'store_code'=>$this->store_code,
            'store_name'=>$this->store->store_name,
            'kyc_for'=>$this->kyc_for,
            'name_in_devanagari'=>$this->name_in_devanagari,
            'name_in_english'=>$this->name_in_english,
            'marital_status'=>$this->marital_status,
            'gender'=>$this->gender,
            'pan_no'=>$this->pan_no,
            'educational_qualification'=>$this->educational_qualification,
            'permanent_house_no'=>$this->permanent_house_no,
            'permanent_street'=>$this->permanent_street,
            'permanent_location_tree'=>(new LocationHierarchyService((new LocationHierarchyRepository)))->getLocationPath($this->permanent_ward_no),
            'present_house_no'=>$this->present_house_no,
            'present_street'=>$this->present_street,
            'present_location_tree'=>(new LocationHierarchyService((new LocationHierarchyRepository)))->getLocationPath($this->present_ward_no),
            'landmark'=>$this->landmark,
            'latitude'=>$this->latitude,
            'longitude'=>$this->longitude,
            'landlord_name'=>$this->landlord_name,
            'landlord_contact_no'=>$this->landlord_contact_no,
            'is_verified' => $this->isVerified(),
            'verified_at' => $this->verified_at,
            'kyc_family_detail' =>new IndividualKycFamilyDetailResource($this->kycFamilyDetail),
           // 'kyc_family_detail' =>$this->kycFamilyDetail,
            'kyc_citizenship_detail'=>new IndividualKycCitizenshipDetailResource($this->kycCitizenshipDetail),
            'kyc_banks_detail'=>KycBankResource::collection($this->kycBanksDetail),
            'kyc_documents' =>KycDocumentResource::collection($this->kycDocuments)
        ];
    }
}