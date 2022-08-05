<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/21/2020
 * Time: 6:01 PM
 */

namespace App\Modules\Store\Services\Kyc;

use App\Modules\Store\Repositories\Kyc\KycVideoAgreementRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class KycVideoAgreementService
{

    private $kycVideoAgreementRepo,$kycAgreementGenerationService;

    public function __construct(KycVideoAgreementRepository $kycVideoAgreementRepository,
                                KycAgreementGenerationService $kycAgreementGenerationService)
    {
        $this->kycVideoAgreementRepo = $kycVideoAgreementRepository;
        $this->kycAgreementGenerationService = $kycAgreementGenerationService;
    }

    public function getKycAgreementVideosOfStore($storeCode){

        return $this->kycVideoAgreementRepo->getVideosOfStore($storeCode);
    }

    public function saveKycVideoAgreement($validatedData){

        try{
            DB::beginTransaction();
            if ($validatedData['agreement_video_for'] == 'samjhauta_patra'){
                $videoAgreement=$this->saveKycVideoAgreementForSamjhautaPatra($validatedData);
            }
            elseif($validatedData['agreement_video_for'] == 'akhtiyari_patra'){
                $videoAgreement=  $this->saveKycVideoAgreementForAkhtiyari($validatedData);
            }

            else{
                throw new Exception('Invalid video for');
            }
            DB::commit();

            return $videoAgreement;

        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    private function saveKycVideoAgreementForSamjhautaPatra($validatedData){
        try{
            $condition =$this->kycAgreementGenerationService->samjhautaPatraGenerationConditionForAuthStore();
            if (!$condition['verified']){
                throw new Exception($condition['message']);
            }

            $storeCode =getAuthStoreCode();
            $agreementVideo=$this->kycVideoAgreementRepo->findByStoreCode($storeCode,'samjhauta_patra');

            if ($agreementVideo){
                throw new Exception('Kyc agreement video already exists for Samjhauta Patra');
            }

            $validatedData['store_code'] =$storeCode;
           return $this->kycVideoAgreementRepo->save($validatedData);
        }catch (Exception $exception){
            throw $exception;
        }
    }

    private function saveKycVideoAgreementForAkhtiyari($validatedData){

        try{
            $condition =$this->kycAgreementGenerationService->akhtiyariPatraGenerationConditionForAuthStore();
            if (!$condition['verified']){
                throw new Exception($condition['message']);
            }

            $storeCode =getAuthStoreCode();
            $agreementVideo=$this->kycVideoAgreementRepo->findByStoreCode($storeCode,'akhtiyari_patra');

            if ($agreementVideo){
                throw new Exception('Kyc agreement video already exists for Akhtiyari Patra');
            }

            $validatedData['store_code'] =$storeCode;
            return $this->kycVideoAgreementRepo->save($validatedData);
        }catch (Exception $exception){
            throw $exception;
        }
    }

}