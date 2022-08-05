<?php

namespace App\Modules\Store\Controllers\Api\Front\Kyc;


use App\Http\Controllers\Controller;
use App\Modules\Store\Requests\Kyc\AgreementVideoRequest;
use App\Modules\Store\Resources\Kyc\KycVideoAgreementResource;
use App\Modules\Store\Services\Kyc\KycAgreementGenerationService;
use App\Modules\Store\Services\Kyc\KycVideoAgreementService;
use Exception;

class KycAgreementVideoController extends Controller
{

    private $kycAgreementGenerationService,$kycVideoAgreementService;

    public function __construct(KycAgreementGenerationService $kycAgreementGenerationService,
                                KycVideoAgreementService $kycVideoAgreementService)
    {
        $this->kycAgreementGenerationService = $kycAgreementGenerationService;
        $this->kycVideoAgreementService = $kycVideoAgreementService;
    }

    public function getKycAgreementVideo(){

        try{
            $storeCode=getAuthStoreCode();
            $agreementVideos = $this->kycVideoAgreementService->getKycAgreementVideosOfStore($storeCode);

            $agreementVideos= KycVideoAgreementResource::collection($agreementVideos);
            return sendSuccessResponse('Data Found',  $agreementVideos);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function storeKycAgreementVideo(AgreementVideoRequest $agreementVideoRequest)
    {
        try{
            $validatedRequest = $agreementVideoRequest->validated();

            $agreementVideo = $this->kycVideoAgreementService->saveKycVideoAgreement($validatedRequest);
            $agreementVideo = new KycVideoAgreementResource($agreementVideo);
            return sendSuccessResponse('You have uploaded agreement video successfully',$agreementVideo);


        }catch(Exception $ex){
            return sendErrorResponse($ex->getMessage(), $ex->getCode());
        }
    }
}
