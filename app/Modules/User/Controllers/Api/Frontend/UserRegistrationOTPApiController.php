<?php

namespace App\Modules\User\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Modules\User\Requests\OTP\UserRegistrationOTPCreateRequest;
use App\Modules\User\Requests\OTP\UserRegistrationOTPVerifyRequest;
use App\Modules\User\Services\UserRegistrationOTPService;
use Exception;

class UserRegistrationOTPApiController extends Controller
{
    private $userRegistrationOTPService;
    public function __construct(UserRegistrationOTPService $userRegistrationOTPService)
    {
        $this->userRegistrationOTPService = $userRegistrationOTPService;
    }

    public function createOTP(UserRegistrationOTPCreateRequest $request){
        try{
            $validatedData = $request->validated();
            $this->userRegistrationOTPService->createOTP($validatedData);
            return sendSuccessResponse('Please enter 4 digit OTP for verification',200);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

    public function verifyOTP(UserRegistrationOTPVerifyRequest $request){
        try{
            $validatedData = $request->validated();
            $this->userRegistrationOTPService->verifyOTP($validatedData);
            return sendSuccessResponse('Account Verification Successful');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

}
