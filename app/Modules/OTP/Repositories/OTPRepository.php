<?php


namespace App\Modules\OTP\Repositories;


use App\Modules\OTP\Models\OTP;
use App\Modules\PricingLink\Models\UserPricingView;
use Carbon\Carbon;

class OTPRepository
{

//    public function getLatestActiveOTPCodeForVerification()
//    {
//        return OTP::where('user_code',getAuthUserCode())
//            ->where('is_active',1)
//            ->where('is_claimed',0)
//            ->latest()
//            ->first();
//    }

//    public function getAllOtpCodeGeneratedTodayByUser($userCode)
//    {
//        return OTP::where('user_code',$userCode)
//            ->whereDate('created_at',Carbon::today())
//            ->select('otp_verification_code')
//            ->count();
//    }

    public function getLatestUseAbleOTPForVerification($entity,$entityCode,$purpose){
        return OTP::where('entity',$entity)
                    ->where('entity_code',$entityCode)
                    ->where('purpose',$purpose)
                    ->where('useable',1)
                    ->latest()
                    ->first();

    }

    public function getLatestOTPForVerification($entity,$entityCode){

        return OTP::where('entity',$entity)
            ->where('entity_code',$entityCode)
            ->latest()
            ->first();

    }

    public function store($otpData)
    {
        return OTP::create($otpData)->fresh();
    }

//    public function update($otpDetail)
//    {
//        return $otpDetail->update([
//            'is_active' => 0,
//            'is_claimed' => 1
//        ]);
//    }

    public function updateUnUseAbleForExpiredOTP($entity,$entityCode,$purpose){
        return  OTP::where('entity',$entity)
                    ->where('entity_code',$entityCode)
                    ->where('purpose',$purpose)
                    ->where('useable',1)
                    ->update(['useable'=>0]);
    }

    public function updateUnUseAbleForVerifiedOTP(OTP $latestOtp){
         $latestOtp->update(['useable'=> 0,'purpose_verified' => 1]);
         return $latestOtp->refresh();
    }

    public function verifyOTPPurpose(OTP $latestOtp){
        $latestOtp->update(['purpose_verified' => 1]);
        return $latestOtp->refresh();
    }

    public function makeOTPUnUsable(OTP $latestOtp){
        $latestOtp->update(['useable'=> 0]);
        return $latestOtp->refresh();
    }

}
