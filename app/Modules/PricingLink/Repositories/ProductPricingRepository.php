<?php


namespace App\Modules\PricingLink\Repositories;

use App\Modules\PricingLink\Models\PricingMaster;
use App\Modules\PricingLink\Models\UserPricingView;
use Illuminate\Support\Facades\Session;


class ProductPricingRepository
{
    public function findPricingLinkByLink($link)
    {
        return PricingMaster::where('link',$link)->where('is_active',1)->first();
    }

    public function storePricingView($validatedData)
    {
        return UserPricingView::create($validatedData);
    }
    public function storeOtpWithoutAuth($otpData,$pricingView)
    {
        return $pricingView->update([
            'otp_code'=>$otpData['otp_code'],
        ]);
    }

    public function getLatestActiveOTPCodeForVerificationOfWithoutAuth($validatedData)
    {
        return UserPricingView::where('otp_code',$validatedData['otp_code'])
            ->where('pricing_master_code',$validatedData['pricing_master_code'])
            ->where('mobile_number',$validatedData['mobile_number'])
            ->where('is_verified',0)
            ->latest()
            ->first();
    }
    public function updateForOtpVerify($otpDetail)
    {
        return $otpDetail->update([
            'is_verified'=>1,
        ]);
    }

    public function setSessionVariable($otpDetail)
    {
      Session::put('pricing_view_session',[
            'user_pricing_view_code'=>$otpDetail->user_pricing_view_code,
            'mobile_number'=>$otpDetail->mobile_number,
            'full_name'=>$otpDetail->full_name,
            'pricing_master_code'=>$otpDetail->pricing_master_code,

        ]);

    }

    public function findPricingViewByMobile($validatedData)
    {
        return UserPricingView::where('pricing_master_code',$validatedData['pricing_master_code'])
            ->where('mobile_number',$validatedData['mobile_number'])
            ->where('is_verified',1)->first();
    }
}
