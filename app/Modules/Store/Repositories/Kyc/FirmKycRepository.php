<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/16/2020
 * Time: 11:49 AM
 */

namespace App\Modules\Store\Repositories\Kyc;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Store\Models\Kyc\FirmKycBankDetail;
use App\Modules\Store\Models\Kyc\FirmKycDocument;
use App\Modules\Store\Models\Kyc\FirmKycMaster;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FirmKycRepository
{
    use ImageService;

    public function getDocumentTypes(){

        return FirmKycDocument::DOCUMENT_TYPES;
    }


    public function findVerifiedFirmKyc($storeCode,$with=[]){
        $firmKyc = FirmKycMaster::with($with)->where('verification_status','verified')->where('store_code',$storeCode)->first();
        if(!$firmKyc){
            throw new Exception('The firm kyc provided is not verified');
        }
        return $firmKyc;
    }

    public function findFirmKyc($storeCode,$with=[]){

        $firmKyc = FirmKycMaster::with($with)->where('store_code',$storeCode)->first();
        return $firmKyc;
    }

    public function findOrFailByCode($code,$with=[]){
        $kyc = FirmKycMaster::with($with)->where('kyc_code',$code)->first();
        if(!$kyc){
            throw new ModelNotFoundException('No Kyc Information !');
        }
        return $kyc;
    }

    public function findOrFailByStoreCode($storeCode,$with=[]){

        $firmKyc = FirmKycMaster::with($with)->where('store_code',$storeCode)->first();
        if(!$firmKyc){
            throw new ModelNotFoundException('No Firm Kyc Information');
        }
        return $firmKyc;
    }

    public function save($validatedData){

        try{
            return FirmKycMaster::updateOrCreate(
                [
                    'store_code' => $validatedData['store_code']
                ], $validatedData
            )->fresh();
        }catch (Exception $e){
            throw $e;
        }

    }

    public function saveKycBanksDetail(FirmKycMaster $KYCMaster,$validatedData){
        try{

            if (isset($validatedData['deleted_bank_code'])){
                $toBeDeletedBankCodes=$validatedData['deleted_bank_code'];
                $KYCMaster->kycBanksDetail()->whereIn('bank_code',$toBeDeletedBankCodes)->delete();
            }

            foreach ($validatedData['bank_code'] as $key=>$bankCode){

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



        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function saveKycDocument(FirmKycMaster $KYCMaster,$document,$documentType){

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

            $fileNameToStore = $this->storeImageInServer($document, FirmKycDocument::DOCUMENT_PATH);

            $KYCMaster->kycDocuments()->updateOrCreate(
                [
                    'kyc_code'=>$KYCMaster->kyc_code,
                    'document_type'=>$documentType
                ],
                [
                    'document_file' => $fileNameToStore,
                ]);

            if ($oldDocumentFile){
                $this->deleteImageFromServer(FirmKycDocument::DOCUMENT_PATH,$oldDocument->document_file);
            }

        }catch (Exception $e){
            $this->deleteImageFromServer(FirmKycDocument::DOCUMENT_PATH,$fileNameToStore);
            throw $e;
        }

    }

    public function updateVerificationStatus(FirmKycMaster $firmKycMaster,$validatedData){

        $firmKycMaster->verification_status = $validatedData['verification_status'];
        $firmKycMaster->remarks = $validatedData['remarks'];
        $firmKycMaster->responded_by = getAuthUserCode();
        $firmKycMaster->responded_at = Carbon::now();
        $firmKycMaster->can_update_kyc = $validatedData['verification_status'] == 'verified' ? 0 : 1;

        $firmKycMaster->save();

        return $firmKycMaster;
    }
    public function getBankDetailsFromFirmKyc($firmKycCode){
        return FirmKycBankDetail::where('kyc_code',$firmKycCode)->get();
    }

    public function getSingleBankDetailFromFirmKyc($firmKycCode,$bankCode){
        return FirmKycBankDetail::where('kyc_code',$firmKycCode)
            ->where('bank_code',$bankCode
            )->with('bank')->firstOrFail();
    }
}
