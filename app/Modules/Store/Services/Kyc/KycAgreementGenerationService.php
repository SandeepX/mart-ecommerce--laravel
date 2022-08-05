<?php

/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/16/2020
 * Time: 11:49 AM
 */

namespace App\Modules\Store\Services\Kyc;

use App\Modules\Store\Repositories\Kyc\FirmKycRepository;
use App\Modules\Store\Repositories\Kyc\IndividualKycRepository;
use Exception;

use Illuminate\Support\Facades\DB;

class KycAgreementGenerationService
{

    private $individualKycRepo, $firmKycRepo;

    public function __construct(
        IndividualKycRepository $individualKycRepository,
        FirmKycRepository $firmKycRepository
    ) {
        $this->individualKycRepo = $individualKycRepository;
        $this->firmKycRepo = $firmKycRepository;
    }

    public function akhtiyariPatraGenerationConditionForAuthStore(){
        try{
            $storeCode = getAuthStoreCode();

            $sanchalakKyc = $this->individualKycRepo->findVerifiedKyc($storeCode, 'sanchalak');
            $akhtiyariKyc = $this->individualKycRepo->findVerifiedKyc($storeCode, 'akhtiyari');
            $firmKyc = $this->firmKycRepo->findVerifiedFirmKyc($storeCode);


            $kycVerificationStatus = [
                'sanchalak' => ($sanchalakKyc) ? 1 : 0,
                'akhtiyari' => ($akhtiyariKyc) ? 1 : 0,
                'firm' => ($firmKyc) ? 1 : 0
            ];

            $unVerifiedKycs =  array_filter($kycVerificationStatus, function ($kycStatus) {
                return $kycStatus == 0;
            });

            if(count($unVerifiedKycs) > 0){
                $unverifiedKycNames = array_keys($unVerifiedKycs);
                $message = 'You have unverified kycs : '.implodeArray($unverifiedKycNames);
               throw new Exception($message,403);
            }

            if ($sanchalakKyc && $akhtiyariKyc && $firmKyc){
                return [
                    'verified'=> true,
                    'sanchalakKyc' => $sanchalakKyc,
                    'akhtiyariKyc' => $akhtiyariKyc,
                    'firmKyc' => $firmKyc,
                    'message'=>'Verified'
                ];
            }

            return [
                'verified'=> false,
                'sanchalakKyc' => $sanchalakKyc,
                'akhtiyariKyc' => $akhtiyariKyc,
                'firmKyc' => $firmKyc,
                'message' => 'Sorry ! you need to verify all (sanchalak,akhtiyari & firm) kyc\'s '
            ];
        }catch (Exception $exception){
            throw $exception;
        }
    }
    public function oldAkhtiyariPatraGenerationCondition()
    {
          // one must have verified firm kyc  and verified sanchalak kyc and verified akhtiyari kyc
        $generationCondition = false;

        try {
            $storeCode = getAuthStoreCode();

            $sanchalakKyc = $this->individualKycRepo->findVerifiedKyc($storeCode, 'sanchalak');
            $akhtiyariKyc = $this->individualKycRepo->findVerifiedKyc($storeCode, 'akhtiyari');
            $firmKyc = $this->firmKycRepo->findVerifiedFirmKyc($storeCode);

            $hasAllVerfiedKycs = true;

            $cannotGenerateMessage = 'Sorry ! you need verified ';

            if (!$sanchalakKyc) {
                $hasAllVerfiedKycs = false;
                $cannotGenerateMessage .= "sanchalak kyc ";
            }

            if (!$akhtiyariKyc) {
                $hasAllVerfiedKycs = false;
                $cannotGenerateMessage .= "akhtiyari kyc ";
            }

            if (!$firmKyc) {
                $hasAllVerfiedKycs = false;
                $cannotGenerateMessage .= " firm kyc .";
            }

            if ($hasAllVerfiedKycs) {
                $generationCondition = true;
            }

            return $generationCondition;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function samjhautaPatraGenerationConditionForAuthStore()
    {
        try{
            $storeCode = getAuthStoreCode();

            $sanchalakKyc = $this->individualKycRepo->findVerifiedKyc($storeCode, 'sanchalak');
//            $akhtiyariKyc = $this->individualKycRepo->findVerifiedKyc($storeCode, 'akhtiyari');
            $firmKyc = $this->firmKycRepo->findVerifiedFirmKyc($storeCode);



//            if ($firmKyc && ($sanchalakKyc || $akhtiyariKyc)){
            if ($firmKyc && $sanchalakKyc){
                return [
                    'verified'=> true,
                    'sanchalakKyc' => $sanchalakKyc,
//                    'akhtiyariKyc' => $akhtiyariKyc,
                    'firmKyc' => $firmKyc,
                    'message'=>'Verified'
                ];
            }

            return [
                'verified'=> false,
                'sanchalakKyc' => $sanchalakKyc,
//                'akhtiyariKyc' => $akhtiyariKyc,
                'firmKyc' => $firmKyc,
                'message' => 'Firm kyc and sanchalak kyc must be verified '
            ];
        }catch (Exception $exception){
            throw $exception;
        }

     }

    public function oldSamjhautaPatraGenerationCondition()
    {
        // one must have firm kyc verfied and verified (sanchalak kyc or akhtiyari kyc)
        $generationCondition = false;

        try{
            $storeCode = getAuthStoreCode();

            $sanchalakKyc = $this->individualKycRepo->findVerifiedKyc($storeCode,'sanchalak');
//            $akhtiyariKyc = $this->individualKycRepo->findVerifiedKyc($storeCode,'akhtiyari');
            $firmKyc = $this->firmKycRepo->findVerifiedFirmKyc($storeCode);

            $hasRequiredVerifiedKycs = true;

            $cannotGenerateMessage = 'Sorry ! you need verified ';

//            if ($sanchalakKyc || $akhtiyariKyc) {
            if ($sanchalakKyc) {
                $hasRequiredVerifiedKycs = true;
            }else{
                $hasRequiredVerifiedKycs = false;
                $cannotGenerateMessage .= " sanchalak kyc  ";
            }


            if (!$firmKyc) {
                $hasRequiredVerifiedKycs = false;
                $cannotGenerateMessage .= " firm kyc .";
            }

            if ($hasRequiredVerifiedKycs) {
                $generationCondition = true;
            }

            return $generationCondition;

        }catch(Exception $ex){
            throw $ex;
        }
    }

}
