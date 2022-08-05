<?php

namespace App\Modules\OTP\Controllers\Api;
use App\Modules\Application\Classes\PhoneNumberValidator;
use App\Modules\OTP\Jobs\AccountVerifications\OTPAccountVerificationJob;
use App\Modules\OTP\Mails\OTPAccountVerificationEmail;
use App\Modules\OTP\Requests\EmailOTPAccountVerificationsRequest;
use App\Modules\OTP\Requests\PhoneOTPAccountVerificationsRequest;
use App\Modules\OTP\Resources\OTPResource;
use App\Modules\OTP\Services\OTPAccountVerificationsService;
use App\Modules\SMSProcessor\Jobs\SendSmsJob;
use App\Modules\User\Jobs\SendMailJob;
use App\Modules\User\Mails\AccountVerificationEmail;
use Illuminate\Support\Facades\Mail;

class OTPAccountVerificationsController {

    protected $otpAccountVerificationsService;

    public function __construct(OTPAccountVerificationsService $OTPAccountVerificationsService)
    {
        $this->otpAccountVerificationsService = $OTPAccountVerificationsService;
    }
    public function generatePhoneOTPVerificationsCode(PhoneOTPAccountVerificationsRequest $request ){

        try{

            $validatedData=$request->validated();
           // PhoneNumberValidator::validatePhoneNumber($validatedData['phone']);
            $phoneOtp =$this->otpAccountVerificationsService->generatePhoneOTPVerificationsCode($validatedData);
            $otp_code=$phoneOtp['otp_code'];
            $phoneNumber=$phoneOtp['otp_source_value'];
            $phoneDetails['purpose'] = 'account_verification';
            $phoneDetails['purpose_code'] = $phoneOtp['id'];
            SendSmsJob::dispatch($phoneNumber,'Account Verification code   '.$otp_code. ' -@ https://allpasal.com/',$phoneDetails);
            $data = new OTPResource($phoneOtp);
            return sendSuccessResponse('Phone OTP has been generated',$data);
        }
        catch (\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }
    public function generateEmailOTPVerificationsCode(EmailOTPAccountVerificationsRequest $request ){
        $validatedData=$request->validated();
        try{
            $emailOtp=$this->otpAccountVerificationsService->generateEmailOTPVerificationsCode($validatedData);
            $otpCode=$emailOtp['otp_code'];
            $email=$emailOtp['otp_source_value'];
            $data =  [];
            $data['otp'] = $otpCode;
            SendMailJob::dispatch($email,new OTPAccountVerificationEmail($data));
            $data= new OTPResource($emailOtp);
            return sendSuccessResponse("Email OTP has been generated",$data);
        }
        catch (\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

}
