<?php


namespace App\Modules\OTP\Controllers\Api;

use App\Modules\OTP\Requests\OtpVerificationRequest;
use App\Modules\OTP\Services\OTPService;
use Illuminate\Http\Request;


class OtpController
{
    private $otpService;

    public function __construct(OTPService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function createOTP(Request $request)
    {
        try{
            $otp = $this->otpService->createOTP();
            return sendSuccessResponse('Please enter 6 digit OTP for verification',200);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function verifyOTP(OtpVerificationRequest $request)
    {
        try{
            $optCode = $request->validated();
            $this->otpService->verifyOTP($optCode['otp_code']);
            return sendSuccessResponse('Verification Successful');
        }catch(\Exception $e){
            return sendErrorResponse($e->getMessage(),400);
        }
    }

}
