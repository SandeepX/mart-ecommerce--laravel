<?php


namespace App\Modules\OTP\Repositories;
use App\Modules\OTP\Models\OtpAccountVerifications;
use Carbon\Carbon;
class OTPAccountVerificationsRepository
{
    public function checkPhoneOTPUsed($phone){
        return OtpAccountVerifications::where('otp_request_source','phone')
            ->where('otp_source_value',$phone)
            ->where('useable',0)
            ->first();
    }
    public function checkEmailOTPUsed($email){
        return OtpAccountVerifications::where('otp_request_source','email')
            ->where('otp_source_value',$email)
            ->where('useable',0)
            ->first();
    }
    public function getLatestUsablePhoneOTP($phone){
        return OtpAccountVerifications::where('otp_request_source','phone')
            ->where('otp_source_value',$phone)
            ->where('useable',1)
            ->first();
    }
    public function getLatestUsableEmailOTP($email){
        return OtpAccountVerifications::where('otp_request_source','email')
            ->where('otp_source_value',$email)
            ->where('useable',1)
            ->first();
    }
    public function verifyPhoneOTPCode($validatedData){
        return OtpAccountVerifications::where('otp_request_source','phone')
            ->where('otp_source_value',$validatedData['phone'])
            ->where('otp_code',$validatedData['otp_code'])
            ->where('useable',1)
            ->first();
    }
    public function verifyEmailOTPCode($validatedData){
        return OtpAccountVerifications::where('otp_request_source','email')
            ->where('otp_source_value',$validatedData['email'])
            ->where('otp_code',$validatedData['otp_code'])
            ->where('useable',1)
            ->first();
    }
    public function generateAccountVerificationsPhoneOTP($validatedPhone){
        return OtpAccountVerifications::create($validatedPhone)->fresh();
    }
    public function generateAccountVerificationsEmailOTP($validatedEmail){
        return OtpAccountVerifications::create($validatedEmail)->fresh();
    }
    public function updateOTPUsable($data){
        $data->update(['useable'=>0]);
        return $data->fresh();
    }

    public function deleteLastUsablePhoneOTP($data){
        return $data->delete();
    }
    public function deleteLastUsableEmailOTP($data){
        return $data->delete();
    }
}
