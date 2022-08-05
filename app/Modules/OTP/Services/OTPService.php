<?php


namespace App\Modules\OTP\Services;

use App\Modules\OTP\Models\OTP;
use App\Modules\OTP\Repositories\OTPRepository;
use App\Modules\PricingLink\Repositories\ProductPricingRepository;
use App\Modules\SMSProcessor\Jobs\SendSmsJob;
use App\Modules\User\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Session;

class OTPService
{
    private $otpRepo;
    private $userRepo;
    private $productPricingRepository;

    public function __construct(OTPRepository $otpRepo,
                                UserRepository $userRepo,
                                ProductPricingRepository $productPricingRepository)
    {
        $this->otpRepo = $otpRepo;
        $this->userRepo = $userRepo;
        $this->productPricingRepository = $productPricingRepository;
    }

    public function createOTP()
    {
        DB::beginTransaction();
        try{
            $user = $this->userRepo->findOrFailUserByCode(getAuthUserCode());

            $getAllOptCreatedToday = $this->otpRepo->getAllOtpCodeGeneratedTodayByUser(getAuthUserCode());

            if($getAllOptCreatedToday > 3){
                throw new Exception('Sorry ! you have reached limit of OTP creation for a day',403);
            }

            $otpData['otp_verification_code'] = OTP::generateCode();
            $otpData['user_code'] = getAuthUserCode();
            $otpData['otp_code'] = random_int(100000, 999999);;
            $otpData['otp_for'] = 'registration';
            $otpData['is_active'] = 1;
            $otpData['is_claimed'] = 0;

            $sms = $this->sendSMS($user->login_phone,$otpData);
            if($sms){
                $otp = $this->otpRepo->store($otpData);
            }
            DB::commit();
            return $otp;

        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function verifyOTP($otpCode)
    {
        DB::beginTransaction();
        try{
            $user = $this->userRepo->findOrFailUserByCode(getAuthUserCode());
            $otpDetail = $this->otpRepo->getLatestActiveOTPCodeForVerification();

            if(!$otpDetail){
                throw new Exception('Found some issue while verifying this OTP, please try again !!',403);
            }

            if($otpCode != $otpDetail->otp_code){
                throw new Exception('OTP does not match, please try again after few seconds !!',403);
            }

            $updateOtpDetail = $this->otpRepo->update($otpDetail);

            if($updateOtpDetail){
                $verifyUserPhone = $this->userRepo->updatePhoneVerificationStatus($user);
            }
            DB::commit();

        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    private function sendSMS($phoneNumber,$otpData)
    {
        $data['purpose'] = 'OTP verification';
        $data['purpose_code'] = $otpData['otp_verification_code'];
        return SendSmsJob::dispatch(
            $phoneNumber,
            'Verification code   '.$otpData['otp_code']. ' -@ https://allpasal.com/',
            $data
        );
    }

    public function createOTPWithoutAuth($pricingView)
    {
        DB::beginTransaction();
        try{

            $otpData['otp_code'] = random_int(100000, 999999);;
            $sms = $this->sendSMSToWithoutAuth($pricingView->mobile_number,$otpData);
            if($sms){
                $otp = $this->productPricingRepository->storeOtpWithoutAuth($otpData,$pricingView);
            }
            DB::commit();
            return $otp;

        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
    public function verifyOTPWithoutAuth($validatedData)
    {
        DB::beginTransaction();
        try{
            $otpDetail = $this->productPricingRepository->getLatestActiveOTPCodeForVerificationOfWithoutAuth($validatedData);
            if(!$otpDetail){
                throw new Exception('Found some issue while verifying this OTP, please try again !!',403);
            }
            if($validatedData['otp_code'] != $otpDetail->otp_code){
                throw new Exception('OTP does not match, please try again after few seconds !!',403);
            }
            $updateOtpDetail = $this->productPricingRepository->updateForOtpVerify($otpDetail);
            $setSession = $this->productPricingRepository->setSessionVariable($otpDetail);
            DB::commit();

        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
    private function sendSMSToWithoutAuth($phoneNumber,$otpData)
    {
        $data['purpose'] = 'OTP verification';
        $data['purpose_code'] = $otpData['otp_code'];
        return SendSmsJob::dispatch(
            $phoneNumber,
            'Verification code   '.$otpData['otp_code']. ' -@ https://allpasal.com/',
            $data
        );
    }
}
