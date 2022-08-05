<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/21/2020
 * Time: 1:28 PM
 */

namespace App\Modules\Store\Transformers;


use App\Modules\Location\Repositories\LocationHierarchyRepository;
use App\Modules\Location\Services\LocationHierarchyService;
use App\Modules\Store\Models\Kyc\FirmKycDocument;
use App\Modules\Store\Models\Kyc\FirmKycMaster;

class FirmKycDetailTransformer
{

    private $firmKycMaster;

    public function __construct(FirmKycMaster $firmKycMaster)
    {
        $this->firmKycMaster = $firmKycMaster;
    }

    public function transform(){
        return [
            'kyc_code'=>$this->firmKycMaster->kyc_code,
            'user_code'=>$this->firmKycMaster->user_code,
            //'submitted_by' => $this->firmKycMaster->submittedBy->name,
            'submitted_by' => ($this->firmKycMaster->submittedBy) ? $this->firmKycMaster->submittedBy->name:'-',
            'responded_by' => $this->firmKycMaster->respondedBy ? $this->firmKycMaster->respondedBy->name : '-',
            'verification_status' => $this->firmKycMaster->verification_status,
            'is_verified' => $this->firmKycMaster->isVerified(),
            'responded_at' => $this->firmKycMaster->responded_at,
            'remarks' => $this->firmKycMaster->remarks ? $this->firmKycMaster->remarks : '-',
            'store_code'=>$this->firmKycMaster->store_code,
            'store_name'=>$this->firmKycMaster->store->store_name,
            'business_name'=>$this->firmKycMaster->business_name,
            'business_capital'=>number_format($this->firmKycMaster->business_capital),
            'business_registered_from'=>$this->firmKycMaster->business_registered_from,
            'business_registered_address'=>$this->firmKycMaster->business_registered_address,
            'store_location_tree'=>(new LocationHierarchyService((new LocationHierarchyRepository())))->getLocationPath($this->firmKycMaster->store_location_ward_no),
            'business_address_latitude'=>$this->firmKycMaster->business_address_latitude,
            'business_address_longitude'=>$this->firmKycMaster->business_address_longitude,
            'business_pan_vat_type'=>$this->firmKycMaster->business_pan_vat_type,
            'business_pan_vat_number'=>$this->firmKycMaster->business_pan_vat_number,
            'business_registration_no'=>$this->firmKycMaster->business_registration_no,
            'business_registered_date'=>$this->firmKycMaster->business_registered_date,
            'purpose_of_business'=>$this->firmKycMaster->purpose_of_business,
            'share_holders_no'=>$this->firmKycMaster->share_holders_no,
            'kyc_banks_detail' =>$this->firmKycMaster->kycBanksDetail->map(function ($bankDetail){
                return [
                    'bank_code' =>$bankDetail->bank_code,
                    'bank_name' =>$bankDetail->bank->bank_name,
                    'bank_branch_name' =>$bankDetail->bank_branch_name,
                    'bank_account_no' =>$bankDetail->bank_account_no,
                    'bank_account_holder_name' =>$bankDetail->bank_account_holder_name,

                ];
            }),

            'kyc_documents' =>$this->firmKycMaster->kycDocuments->map(function ($document){

                return [
                    'document_type' =>$document->document_type,
                    'document_file' => photoToUrl($document->document_file,asset(FirmKycDocument::DOCUMENT_PATH))
                ];
            }),
            'can_update_kyc' => $this->firmKycMaster->can_update_kyc,
            'update_request_allowed_by' => $this->firmKycMaster->kycUpdateRequestProvider ?  $this->firmKycMaster->kycUpdateRequestProvider->name : '',
            'update_request_allowed_at' => $this->firmKycMaster->update_request_allowed_at
        ];
    }
}
