<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/20/2020
 * Time: 2:29 PM
 */

namespace App\Modules\Store\Transformers;


use App\Modules\Location\Repositories\LocationHierarchyRepository;
use App\Modules\Location\Services\LocationHierarchyService;
use App\Modules\Store\Models\Kyc\IndividualKYCDocument;
use App\Modules\Store\Models\Kyc\IndividualKYCMaster;

class IndividualKycDetailTransformer
{
    private $individualKycMaster;

    public function __construct(IndividualKYCMaster $individualKYCMaster)
    {
        $this->individualKycMaster = $individualKYCMaster;
    }

    public function transform(){
        return [
            'kyc_code'=>$this->individualKycMaster->kyc_code,
            'user_code'=>$this->individualKycMaster->user_code,
           // 'submitted_by' =>  $this->individualKycMaster->submittedBy->name,
            'submitted_by' => ($this->individualKycMaster->submittedBy) ? $this->individualKycMaster->submittedBy->name:'-',
            'responded_by' => $this->individualKycMaster->respondedBy ? $this->individualKycMaster->respondedBy->name : '-',
            'verification_status' => $this->individualKycMaster->verification_status,
            'is_verified' => $this->individualKycMaster->isVerified(),
            'responded_at' => $this->individualKycMaster->responded_at,
            'remarks' => $this->individualKycMaster->remarks ? $this->individualKycMaster->remarks : '-',
            'store_code'=>$this->individualKycMaster->store_code,
            'store_name'=>$this->individualKycMaster->store->store_name,
            'kyc_for'=>$this->individualKycMaster->kyc_for,
            'name_in_devanagari'=>$this->individualKycMaster->name_in_devanagari,
            'name_in_english'=>$this->individualKycMaster->name_in_english,
            'marital_status'=>$this->individualKycMaster->marital_status,
            'gender'=>($this->individualKycMaster->gender)?$this->individualKycMaster->gender:'N/A',
            'pan_no'=>$this->individualKycMaster->pan_no,
            'educational_qualification'=>$this->individualKycMaster->educational_qualification,
            'permanent_house_no'=>$this->individualKycMaster->permanent_house_no,
            'permanent_street'=>$this->individualKycMaster->permanent_street,
            'permanent_location_tree'=>(new LocationHierarchyService((new LocationHierarchyRepository())))->getLocationPath($this->individualKycMaster->permanent_ward_no),
            'present_house_no'=>$this->individualKycMaster->present_house_no,
            'present_street'=>$this->individualKycMaster->present_street,
            'present_location_tree'=>(new LocationHierarchyService((new LocationHierarchyRepository)))->getLocationPath($this->individualKycMaster->present_ward_no),
            'landmark'=>$this->individualKycMaster->landmark,
            'latitude'=>$this->individualKycMaster->latitude,
            'longitude'=>$this->individualKycMaster->longitude,
            'landlord_name'=>($this->individualKycMaster->landlord_name) ? $this->individualKycMaster->landlord_name:'N/A',
            'landlord_contact_no'=>($this->individualKycMaster->landlord_contact_no) ? $this->individualKycMaster->landlord_contact_no:'N/A',
//            'kyc_family_detail' => [
//                'spouse_name'=>$this->individualKycMaster->kycFamilyDetail->spouse_name,
//                'father_name'=>$this->individualKycMaster->kycFamilyDetail->father_name,
//                'mother_name'=>$this->individualKycMaster->kycFamilyDetail->mother_name,
//                'grand_father_name'=>$this->individualKycMaster->kycFamilyDetail->grand_father_name,
//                'grand_mother_name'=>$this->individualKycMaster->kycFamilyDetail->grand_mother_name,
//                'sons'=>json_decode($this->individualKycMaster->kycFamilyDetail->sons),
//                // 'sons'=>[1,2,4],
//                'daughters'=>json_decode($this->individualKycMaster->kycFamilyDetail->daughters),
//                'daughter_in_laws'=>json_decode($this->individualKycMaster->kycFamilyDetail->daughter_in_laws),
//                'father_in_law'=>$this->individualKycMaster->kycFamilyDetail->father_in_law,
//                'mother_in_law'=>$this->individualKycMaster->kycFamilyDetail->mother_in_law
//            ],
            'kyc_citizenship_detail' => [
                'citizenship_no'=>$this->individualKycMaster->kycCitizenshipDetail->citizenship_no,
                'citizenship_full_name'=>$this->individualKycMaster->kycCitizenshipDetail->citizenship_full_name,
                'citizenship_nationality'=>$this->individualKycMaster->kycCitizenshipDetail->citizenship_nationality,
                'citizenship_issued_date'=>$this->individualKycMaster->kycCitizenshipDetail->citizenship_issued_date,
                'citizenship_gender'=>$this->individualKycMaster->kycCitizenshipDetail->citizenship_gender,
                'citizenship_birth_place'=>$this->individualKycMaster->kycCitizenshipDetail->citizenship_birth_place,
                'citizenship_district'=>$this->individualKycMaster->kycCitizenshipDetail->citizenship_district,
//                'citizenship_municipality'=>$this->individualKycMaster->kycCitizenshipDetail->citizenship_municipality,
//                'citizenship_ward_no'=>$this->individualKycMaster->kycCitizenshipDetail->citizenship_ward_no,
                'citizenship_dob'=>$this->individualKycMaster->kycCitizenshipDetail->citizenship_dob,
                'citizenship_father_name'=>$this->individualKycMaster->kycCitizenshipDetail->citizenship_father_name,
//                'citizenship_father_address'=>$this->individualKycMaster->kycCitizenshipDetail->citizenship_father_address,
//                'citizenship_father_nationality'=>$this->individualKycMaster->kycCitizenshipDetail->citizenship_father_nationality,
                'citizenship_mother_name'=>$this->individualKycMaster->kycCitizenshipDetail->citizenship_mother_name,
//                'citizenship_mother_address'=>$this->individualKycMaster->kycCitizenshipDetail->citizenship_mother_address,
//                'citizenship_mother_nationality'=>$this->individualKycMaster->kycCitizenshipDetail->citizenship_mother_nationality,
                'citizenship_spouse_name'=>($this->individualKycMaster->kycCitizenshipDetail->citizenship_spouse_name)?($this->individualKycMaster->kycCitizenshipDetail->citizenship_spouse_name):'N/A',
//                'citizenship_spouse_address'=>$this->individualKycMaster->kycCitizenshipDetail->citizenship_spouse_address,
//                'citizenship_spouse_nationality'=>($this->individualKycMaster->kycCitizenshipDetail->citizenship_spouse_nationality) ? $this->individualKycMaster->kycCitizenshipDetail->citizenship_spouse_nationality:'N/A',
                'citizenship_grandfather_name'=>($this->individualKycMaster->kycCitizenshipDetail->citizenship_grandfather_name)? $this->individualKycMaster->kycCitizenshipDetail->citizenship_grandfather_name:'N/A',
                //'citizenship_grandfather_nationality'=>$this->individualKycMaster->kycCitizenshipDetail->citizenship_grandfather_nationality
            ],
            'kyc_banks_detail' => $this->individualKycMaster->kycBanksDetail->map(function ($bankDetail){
                return [
                    'bank_code' =>$bankDetail->bank_code,
                    'bank_name' =>$bankDetail->bank->bank_name,
                    'bank_branch_name' =>$bankDetail->bank_branch_name,
                    'bank_account_no' =>$bankDetail->bank_account_no,
                    'bank_account_holder_name' =>$bankDetail->bank_account_holder_name,

                ];
            }),

            'kyc_documents' =>$this->individualKycMaster->kycDocuments->map(function ($document){

                return [
                    'document_type' =>$document->document_type,
                    'document_file' => photoToUrl($document->document_file,asset(IndividualKYCDocument::DOCUMENT_PATH))
                ];
            }),
            'can_update_kyc' => $this->individualKycMaster->can_update_kyc,
            'update_request_allowed_by' => $this->individualKycMaster->kycUpdateRequestProvider ?  $this->individualKycMaster->kycUpdateRequestProvider->name : '',
            'update_request_allowed_at' => $this->individualKycMaster->update_request_allowed_at
             //'kyc_family_detail' =>$this->individualKycMaster->kycFamilyDetail->toArray(),
           // 'kyc_citizenship_detail'=>new IndividualKycCitizenshipDetailResource($this->individualKycMaster->kycCitizenshipDetail),
            //'kyc_banks_detail'=>KycBankResource::collection($this->individualKycMaster->kycBanksDetail),
           // 'kyc_documents' =>KycDocumentResource::collection($this->individualKycMaster->kycDocuments)
        ];
    }

}
