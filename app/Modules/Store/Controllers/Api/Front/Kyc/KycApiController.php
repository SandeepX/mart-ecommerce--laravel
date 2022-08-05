<?php

namespace App\Modules\Store\Controllers\Api\Front\Kyc;


use App\Http\Controllers\Controller;
use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Store\Requests\Kyc\FirmKycBankDetailRequest;
use App\Modules\Store\Requests\Kyc\FirmKycDocumentRequest;
use App\Modules\Store\Requests\Kyc\FirmKycRequest;

use App\Modules\Store\Resources\Kyc\FirmKycResource;
use App\Modules\Store\Services\Kyc\FirmKycService;
use App\Modules\Store\Services\Kyc\KycApiService;
use Exception;
use Illuminate\Http\Request;

class KycApiController extends Controller
{


    private $kycApiService;

    public function __construct(KycApiService $kycApiService)
    {
        $this->kycApiService = $kycApiService;
    }

    public function getBankDetailsAddedInKYC(Request $request,$storeCode){

        try{
            $kycType=$request->get('kyc_type');
            $KYCBankDetails = $this->kycApiService->getBankDetailsAddedInKYC($kycType,$storeCode);
            return sendSuccessResponse('Bank Details Fetched !',$KYCBankDetails);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),404);
        }
    }

}
