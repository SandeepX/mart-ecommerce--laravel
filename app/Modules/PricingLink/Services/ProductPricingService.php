<?php


namespace App\Modules\PricingLink\Services;


use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\OTP\Services\OTPService;
use App\Modules\PricingLink\Repositories\PricingMasterRepository;
use App\Modules\PricingLink\Repositories\ProductPricingRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProductPricingService
{
    use ImageService;

    private $productPricingRepository;
    private $otpService;
    private $pricingMasterRepository;

    public function __construct(ProductPricingRepository $productPricingRepository,
                                OTPService $otpService,
                                 PricingMasterRepository $pricingMasterRepository)
    {
        $this->productPricingRepository = $productPricingRepository;
        $this->otpService = $otpService;
        $this->pricingMasterRepository = $pricingMasterRepository;
    }

    public function findPricingLinkByLink($link)
    {
        try{
            return $this->productPricingRepository->findPricingLinkByLink($link);

        }catch(\Exception $exception){
            throw $exception;
        }
    }

    public function findPricingLinkByLinkCode($linkCode)
    {
        try{
            return $this->pricingMasterRepository->getPricingLinkByLinkCode($linkCode);

        }catch(\Exception $exception){
            throw $exception;
        }
    }


    public function storePricingView($validatedData)
    {
        try{
            $singlePricingView = $this->productPricingRepository->findPricingViewByMobile($validatedData);
            if(!$singlePricingView){
                $pricingView = $this->productPricingRepository->storePricingView($validatedData);
                $otp = $this->otpService->createOTPWithoutAuth($pricingView);

                return $pricingView;
            }
            else{
                $this->productPricingRepository->setSessionVariable($singlePricingView);
                return $singlePricingView;
            }

        }catch(\Exception $exception){
            throw $exception;
        }
    }
    public function findPricingLinkByOtpCode($otpCode)
    {
        try{
            return $this->productPricingRepository->getLatestActiveOTPCodeForVerificationOfWithoutAuth($otpCode);

        }catch(\Exception $exception){
            throw $exception;
        }
    }
}
