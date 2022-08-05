<?php


namespace App\Modules\Store\Repositories\Kyc;


use App\Modules\Application\Traits\UploadImage\ImageService;

use App\Modules\Store\Models\Kyc\IndividualKYCBankDetail;
use App\Modules\Store\Models\Kyc\IndividualKYCDocument;
use Carbon\Carbon;
use Exception;
use App\Modules\Store\Models\Kyc\IndividualKYCMaster;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class IndividualKycRepository
{
    use ImageService;

    public function getDocumentTypes(){

        return IndividualKYCDocument::DOCUMENT_TYPES;
    }

    public function findVerifiedKyc($storeCode,$kycFor,$with=[]){
        $kyc = IndividualKYCMaster::with($with)->where('verification_status','verified')->where('store_code',$storeCode)->where('kyc_for',$kycFor)->first();
        if(!$kyc){
            throw new Exception('The kyc provided is not verified');
        }
        return $kyc;

    }

    public function findKyc($storeCode,$kycFor,$with=[]){

        $kyc = IndividualKYCMaster::with($with)->where('store_code',$storeCode)->where('kyc_for',$kycFor)->first();
        return $kyc;

    }



    public function findOrFailByCode($code,$with=[]){
        $kyc = IndividualKYCMaster::with($with)->where('kyc_code',$code)->first();
        if(!$kyc){
            throw new ModelNotFoundException('No Kyc Information !');
        }
        return $kyc;
    }

    public function findOrFailByStoreCode($storeCode,$kycFor,$with=[]){

        $kyc = IndividualKYCMaster::with($with)->where('store_code',$storeCode)->where('kyc_for',$kycFor)->first();
        if(!$kyc){
            throw new ModelNotFoundException('No Kyc Information !');
        }
        return $kyc;

    }

    public function save($validatedData){

        try{
            return IndividualKycMaster::updateOrCreate(
                [
                'store_code' => $validatedData['store_code'],
                'kyc_for' => $validatedData['kyc_for']
                ], $validatedData
                )->fresh();
        }catch (Exception $e){
            throw $e;
        }

    }

    public function saveKycCitizenShipDetail(IndividualKYCMaster $KYCMaster,$validatedData){
        try{
            $KYCMaster->kycCitizenshipDetail()->updateOrCreate(
                ['kyc_code'=>$KYCMaster->kyc_code],$validatedData);
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function saveKycFamilyDetail(IndividualKYCMaster $KYCMaster,$validatedData){
        try{
            if (isset($validatedData['sons'])){
                $validatedData['sons'] =json_encode(array_filter($validatedData['sons']));
            }else{
                $validatedData['sons'] = NULL;
            }

            if (isset($validatedData['daughters'])){
                $validatedData['daughters'] =json_encode(array_filter($validatedData['daughters']));
            }else{
                $validatedData['daughters'] = NULL;
            }
            if (isset($validatedData['daughter_in_laws'])){
                $validatedData['daughter_in_laws'] =json_encode(array_filter($validatedData['daughter_in_laws']));
            }else{
                $validatedData['daughter_in_laws'] = NULL;
            }

            $KYCMaster->kycFamilyDetail()->updateOrCreate(
                ['kyc_code'=>$KYCMaster->kyc_code],$validatedData);

        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function saveKycBanksDetail(IndividualKYCMaster $KYCMaster,$validatedData){
        try{
           // dd($validatedData['bank_code']);

            //dd($validatedData);


            if (isset($validatedData['deleted_bank_code'])){
                $toBeDeletedBankCodes=$validatedData['deleted_bank_code'];
                $KYCMaster->kycBanksDetail()->whereIn('bank_code',$toBeDeletedBankCodes)->delete();
            }

            foreach ($validatedData['bank_code'] as $key=>$bankCode){

                if($bankCode != null)
                {
                    $KYCMaster->kycBanksDetail()->updateOrCreate(
                        [
                            'kyc_code'=>$KYCMaster->kyc_code,
                            'bank_code'=>$bankCode
                        ],
                        [
                            'bank_branch_name' => $validatedData['bank_branch_name'][$key],
                            'bank_account_no' => $validatedData['bank_account_no'][$key],
                            'bank_account_holder_name' => $validatedData['bank_account_holder_name'][$key],
                        ]
                    );
                }

            }


        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function saveKycDocument(IndividualKYCMaster $KYCMaster,$document,$documentType){

        $fileNameToStore='';
        $oldDocumentFile='';
        try{
            if (!in_array($documentType,$this->getDocumentTypes())){
                throw new Exception('Invalid document type');
            }

            $oldDocument= $KYCMaster->kycDocuments()->where('document_type',$documentType)->first();

            if ($oldDocument){
                $oldDocumentFile =$oldDocument->document_file;
                //suru mai file delete garera risk kina ..if exception?
            }

            $fileNameToStore = $this->storeImageInServer($document, IndividualKYCDocument::DOCUMENT_PATH);

            $KYCMaster->kycDocuments()->updateOrCreate(
                [
                    'kyc_code'=>$KYCMaster->kyc_code,
                    'document_type'=>$documentType
                ],
                [
                'document_file' => $fileNameToStore,
            ]);

            if ($oldDocumentFile){
                $this->deleteImageFromServer(IndividualKYCDocument::DOCUMENT_PATH,$oldDocument->document_file);
            }

        }catch (Exception $e){
            $this->deleteImageFromServer(IndividualKYCDocument::DOCUMENT_PATH,$fileNameToStore);
            throw $e;
        }

    }


    public function updateVerificationStatus(IndividualKYCMaster $individualKYCMaster,$validatedData){

        $individualKYCMaster->verification_status = $validatedData['verification_status'];
        $individualKYCMaster->remarks = $validatedData['remarks'];
        $individualKYCMaster->responded_by = getAuthUserCode();
        $individualKYCMaster->responded_at = Carbon::now();
        $individualKYCMaster->can_update_kyc = $validatedData['verification_status'] == 'verified' ? 0 : 1;

        $individualKYCMaster->save();

        return $individualKYCMaster;
    }

    public function getBankDetailsFromIndividualKyc($iKycCode){
        return IndividualKYCBankDetail::where('kyc_code',$iKycCode)->with('bank')->get();
    }

    public function getSingleBankDetailFromIndividualKyc($iKycCode,$bankCode){
        $kyc = IndividualKYCBankDetail::where('kyc_code',$iKycCode)
            ->where('bank_code',$bankCode)->with('bank')->first();
        if(!$kyc){
            throw new Exception('No Bank Details Found for the kyc provided');
        }
        return $kyc;
    }
}
