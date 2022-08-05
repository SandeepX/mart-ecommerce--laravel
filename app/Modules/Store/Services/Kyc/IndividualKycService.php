<?php
namespace App\Modules\Store\Services\Kyc;

use App\Modules\Store\Helpers\IndividualKycQueryHelper;
use App\Modules\Store\Repositories\Kyc\IndividualKycRepository;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class IndividualKycService
{

    private $individualKycRepo;

    public function __construct(IndividualKycRepository $individualKycRepo)
    {

        $this->individualKycRepo = $individualKycRepo;
    }

    public function findVerifiedKycByType($kycFor){
        $storeCode = getAuthStoreCode();
        return $this->individualKycRepo->findVerifiedKyc($storeCode,$kycFor);
    }



    public function getAuthStoreKyc($kycFor){
        try{
            $storeCode = getAuthStoreCode();
            $firmKyc = $this->individualKycRepo->findOrFailByStoreCode($storeCode,$kycFor,
                ['kycFamilyDetail','kycCitizenshipDetail','kycDocuments','kycBanksDetail','kycBanksDetail.bank']);

            return $firmKyc;

        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function findOrFailIndividualKycCode($kycCode){
        try{
            $firmKyc = $this->individualKycRepo->findOrFailByCode($kycCode);

            return $firmKyc;

        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function findOrFailIndividualKycEagerByCode($kycCode){
        try{
            $firmKyc = $this->individualKycRepo->findOrFailByCode($kycCode,
                ['store','submittedBy','respondedBy','kycFamilyDetail','kycCitizenshipDetail','kycDocuments','kycBanksDetail','kycBanksDetail.bank']);

            return $firmKyc;

        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function saveKyc($validatedData, $userCode, $storeCode)
    {

        try {
            DB::beginTransaction();

            $kycForType = $validatedData['kyc_data']['kyc_for'];

            if (!IndividualKycQueryHelper::canUpdateKyc($storeCode,$kycForType)){
                throw new Exception('cannot update the kyc right now :'.$kycForType);
            }


            $validatedData['kyc_data']['user_code'] = $userCode;
            $validatedData['kyc_data']['store_code'] = $storeCode;
            $validatedData['kyc_data']['verification_status'] ='pending';
            $kycMaster = $this->individualKycRepo->save($validatedData['kyc_data']);
            $this->individualKycRepo->saveKycCitizenShipDetail($kycMaster, $validatedData['citizenship_data']);
            //$this->individualKycRepo->saveKycFamilyDetail($kycMaster, $validatedData['family_detail_data']);
            if(isset($validatedData['bank_data']['bank_code'])  && (count($validatedData['bank_data']['bank_code']) > 0)){

                $this->individualKycRepo->saveKycBanksDetail($kycMaster, $validatedData['bank_data']);
            }
            if (isset($validatedData['document_data']['citizenship_front'])) {
                $this->individualKycRepo->saveKycDocument($kycMaster, $validatedData['document_data']['citizenship_front'], 'citizenship_front');
            }

            if (isset($validatedData['document_data']['citizenship_back'])) {
                $this->individualKycRepo->saveKycDocument($kycMaster, $validatedData['document_data']['citizenship_back'], 'citizenship_back');
            }

            DB::commit();
            return $kycMaster;

        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function saveAuthKyc($validatedData)
    {
        try {
            $kyc = $this->saveKyc($validatedData, getAuthUserCode(), getAuthStoreCode());
            return $kyc;

        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function respondToIndividualKycByAdmin($validatedData,$kycCode){
        try{
            $individualKyc = $this->individualKycRepo->findOrFailByCode($kycCode);

            if ($individualKyc->isVerified()){
                throw new Exception('Following kyc was already verified at '.$individualKyc->responded_at);
            }
            $validatedData['remarks']=$validatedData['remarks'] ? $validatedData['remarks']:null;
            $individualKyc=$this->individualKycRepo->updateVerificationStatus($individualKyc,$validatedData);

            return $individualKyc;
        }catch (Exception $exception){
            throw $exception;
        }
    }


    public function allowIndividualKycUpdateRequest($kycCode){
        $kyc = $this->findOrFailIndividualKycCode($kycCode);
        if($kyc->verification_status != 'verified' && $kyc->can_update_kyc != 0 ){
           throw  new Exception('Kyc must have been verified first to allow this request');
        }
        try{
            DB::beginTransaction();

            $kyc->can_update_kyc = 1;
            $kyc->update_request_allowed_by = getAuthUserCode();
            $kyc->update_request_allowed_at = Carbon::now();
            $kyc->save();

            DB::commit();
            return $kyc;
        }catch (Exception $ex){
            DB::rollBack();
            throw $ex;
        }

    }

}
