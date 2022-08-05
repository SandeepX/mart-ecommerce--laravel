<?php

namespace App\Modules\Store\Controllers\Api\Front\Dashboard;

use App\Http\Controllers\Controller;
use App\Modules\SalesManager\Requests\ManagerOTPVerificationRequest;
use App\Modules\Store\Requests\StoreOTPVerificationRequest;
use App\Modules\Store\Services\StoreOtpService;
use Exception;

class StoreProfileApiController extends Controller
{

    protected $storeOTPService;

    public function __construct(StoreOtpService $storeOTPService)
    {
        $this->storeOTPService = $storeOTPService;
    }

    public function generatePhoneVerificationOTP(){
        try{
            $this->storeOTPService->generatePhoneVerificationOTP();
            return sendSuccessResponse('Your otp has successfully sent to you phone ');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),400);
        }
    }

    public function generateEmailVerificationOTP(){
        try{
            $this->storeOTPService->generateEmailVerificationOTP();
            return sendSuccessResponse('Your otp code has successfully sent to you email');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),400);
        }

    }

    public function verifyPhoneOTP(StoreOTPVerificationRequest $request){
        try{
            $validatedData = $request->validated();
            $this->storeOTPService->verifyPhoneOTP($validatedData);
            return sendSuccessResponse('Your phone has verified successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),400);
        }
    }

    public function verifyEmailOTP(StoreOTPVerificationRequest $request){
        try{
            $validatedData = $request->validated();
            $this->storeOTPService->verifyEmailOTP($validatedData);
            return sendSuccessResponse('Your email has verified successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),400);
        }
    }



}
