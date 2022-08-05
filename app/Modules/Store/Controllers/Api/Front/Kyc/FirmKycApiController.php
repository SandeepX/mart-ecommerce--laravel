<?php

namespace App\Modules\Store\Controllers\Api\Front\Kyc;


use App\Http\Controllers\Controller;
use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Store\Requests\Kyc\FirmKycBankDetailRequest;
use App\Modules\Store\Requests\Kyc\FirmKycDocumentRequest;
use App\Modules\Store\Requests\Kyc\FirmKycRequest;

use App\Modules\Store\Resources\Kyc\FirmKycResource;
use App\Modules\Store\Services\Kyc\FirmKycService;
use Exception;

class FirmKycApiController extends Controller
{
    use ImageService;

    private $firmKycService;

    public function __construct(FirmKycService $firmKycService)
    {
        $this->firmKycService = $firmKycService;
    }

    public function getFirmKyc(){

        try{
            $firmKyc = $this->firmKycService->getAuthStoreFirmKyc();
            $firmKyc = new FirmKycResource($firmKyc);
            return sendSuccessResponse('Data Found',  $firmKyc);
        }catch (Exception $exception){
            return sendErrorResponse('No Firm Kyc Information',404);
        }
    }

    public function storeFirmKyc(
        FirmKycRequest $kycMasterRequest,
        FirmKycBankDetailRequest $kycBankRequest,
        FirmKycDocumentRequest $kycDocumentRequest
    ){

        try{

            $validatedKycData = $kycMasterRequest->validated();
            $validatedBankRequest = $kycBankRequest->validated();
            $validatedDocumentRequest = $kycDocumentRequest->validated();

            $validatedData=[
                'kyc_data' => $validatedKycData,
                'bank_data' => $validatedBankRequest,
                'document_data' => $validatedDocumentRequest,
            ];

            $kyc = $this->firmKycService->saveAuthKyc($validatedData);
            
            $kyc = new FirmKycResource($kyc);

            return sendSuccessResponse('You have submitted the kyc successfully',$kyc);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }
}