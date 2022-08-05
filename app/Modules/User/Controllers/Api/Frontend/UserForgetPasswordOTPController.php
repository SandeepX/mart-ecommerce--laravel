<?php

namespace App\Modules\User\Controllers\Api\Frontend;
use App\Modules\User\Requests\OTP\UserForgetPasswordOTPCreateRequest;
use App\Modules\User\Requests\OTP\UserForgetPasswordOTPVerifyRequest;
use App\Modules\User\Services\UserForgetPasswordOtpService;
use Exception;

class UserForgetPasswordOTPController
{

    public $userForgetPasswordOtpService;

    public function __construct(UserForgetPasswordOtpService $userForgetPasswordOtpService)
    {
        $this->userForgetPasswordOtpService = $userForgetPasswordOtpService;
    }

    public function generateOTP(UserForgetPasswordOTPCreateRequest $request){
        try{
           $validatedData = $request->validated();
           $otp =  $this->userForgetPasswordOtpService->createOTP($validatedData);
            return sendSuccessResponse('OTP has been sent to your '.$otp->otp_request_via.' ');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

    public function verifyOTP(UserForgetPasswordOTPVerifyRequest $request){
        try{
            $validatedData = $request->validated();
            $this->userForgetPasswordOtpService->verifyOTP($validatedData);
            return sendSuccessResponse('Your otp has been verified');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }



}
