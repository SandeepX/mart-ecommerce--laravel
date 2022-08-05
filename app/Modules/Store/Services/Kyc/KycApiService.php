<?php

/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/16/2020
 * Time: 11:49 AM
 */

namespace App\Modules\Store\Services\Kyc;

use App\Modules\Store\Helpers\FirmKycQueryHelper;
use App\Modules\Store\Models\Kyc\FirmKycMaster;
use App\Modules\Store\Repositories\Kyc\FirmKycRepository;
use App\Modules\Store\Repositories\Kyc\IndividualKycRepository;
use App\Modules\Store\Repositories\Kyc\KycApiRepository;
use App\Modules\Store\Resources\Kyc\KycBankResource;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class KycApiService
{

    private $kycApiRepository;

    public function __construct(
        IndividualKycRepository $individualKycRepository,
        FirmKycRepository $firmKycRepository

    )
    {
        $this->individualKycRepository = $individualKycRepository;
        $this->firmKycRepository = $firmKycRepository;
    }

    public function getBankDetailsAddedInKYC($kycType,$storeCode)
    {

//        $kycTypes = ['sanchalak','akhtiyari','firm'];
        $kycTypes = ['sanchalak','firm'];
        $bankDetails = collect();
        try{
            if(!in_array($kycType,$kycTypes)){
               throw new Exception('Invalid Kyc Types');
            }
            if($kycType == 'sanchalak'){
                $sanchalakKyc = $this->individualKycRepository->findVerifiedKyc($storeCode,'sanchalak');
                $bankDetails = $this->individualKycRepository->getBankDetailsFromIndividualKyc($sanchalakKyc->kyc_code);
            }
//            if($kycType == 'akhtiyari'){
//                $akhtiyariKyc = $this->individualKycRepository->findVerifiedKyc($storeCode,'akhtiyari');
//                $bankDetails = $this->individualKycRepository->getBankDetailsFromIndividualKyc($akhtiyariKyc->kyc_code);
//            }
            if($kycType == 'firm'){
              $firmKyc = $this->firmKycRepository->findVerifiedFirmKyc($storeCode);
              $bankDetails = $this->firmKycRepository->getBankDetailsFromFirmKyc($firmKyc->kyc_code);
            }
            return KycBankResource::collection($bankDetails);
        }catch (Exception $exception){
            throw $exception;
        }

    }

}
