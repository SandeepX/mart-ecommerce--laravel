<?php

namespace App\Modules\Store\Controllers\Api\Front\Kyc;


use App\Http\Controllers\Controller;
use App\Modules\Store\Requests\Kyc\IndividualKycBankDetailRequest;
use App\Modules\Store\Requests\Kyc\IndividualKycCitizenshipRequest;
use App\Modules\Store\Requests\Kyc\IndividualKycDocumentRequest;
use App\Modules\Store\Requests\Kyc\IndividualKycFamilyDetailRequest;
use App\Modules\Store\Requests\Kyc\IndividualKycRequest;
use App\Modules\Store\Resources\Kyc\IndividualKycResource;
use App\Modules\Store\Services\Kyc\IndividualKycService;
use Exception;

class IndividualKycApiController extends Controller
{

    private $individualKycService;

    public function __construct(IndividualKycService $individualKycService)
    {
        $this->individualKycService = $individualKycService;
    }

    public function getIndividualKyc($kycFor){
        try{
            $individualKyc = $this->individualKycService->getAuthStoreKyc($kycFor);
            $individualKyc= new IndividualKycResource($individualKyc);
            return sendSuccessResponse('Data Found',  $individualKyc);
        }catch (Exception $exception){
            return sendErrorResponse('Cannot get '.$kycFor.' kyc information', 404);
        }
    }

    public function storeIndividualKyc(
        IndividualKycRequest $kycMasterRequest,
        IndividualKycCitizenshipRequest $kycCitizenshipRequest,
        IndividualKycBankDetailRequest $kycBankRequest,
        IndividualKycDocumentRequest $kycDocumentRequest
       // IndividualKycFamilyDetailRequest $kycFamilyDetailRequest
    ){

        try{
            $validatedKycData = $kycMasterRequest->validated();
            $validatedCitizenshipData = $kycCitizenshipRequest->validated();
            $validatedBankRequest = $kycBankRequest->validated();
            $validatedDocumentRequest = $kycDocumentRequest->validated();
            //$validatedFamilyDetailRequest = $kycFamilyDetailRequest->validated();




            $validatedData=[
                'kyc_data' => $validatedKycData,
                'citizenship_data' => $validatedCitizenshipData,
                'bank_data' => $validatedBankRequest,
                'document_data' => $validatedDocumentRequest
                //'family_detail_data' => $validatedFamilyDetailRequest,
            ];



            $kycMaster = $this->individualKycService->saveAuthKyc($validatedData);
            $kycData = new IndividualKycResource($kycMaster);
            return sendSuccessResponse(
                'You have submitted '.$validatedKycData['kyc_for'].' kyc form successfully',
                $kycData
                );
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());

        }

    }
}
