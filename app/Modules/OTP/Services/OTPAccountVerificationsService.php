<?php


namespace App\Modules\OTP\Services;


use App\Modules\OTP\Repositories\OTPAccountVerificationsRepository;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\User\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OTPAccountVerificationsService
{
    protected $OTPAccountVerificationsRepository,$userRepository,$storeRepository;
    public function __construct(OTPAccountVerificationsRepository $OTPAccountVerificationsRepository,
                                UserRepository $userRepository,StoreRepository $storeRepository){

        $this->OTPAccountVerificationsRepository=$OTPAccountVerificationsRepository;
        $this->userRepository=$userRepository;
        $this->storeRepository=$storeRepository;
    }

    public function getLatestUsablePhoneOTP($phone){
        return $this->OTPAccountVerificationsRepository->getLatestUsablePhoneOTP($phone);
    }
    public function getLatestUsableEmailOTP($email){
        return $this->OTPAccountVerificationsRepository->getLatestUsableEmailOTP($email);
    }

    public function generatePhoneOTPVerificationsCode($validatedData){

        DB::beginTransaction();
        try{
            $validatedPhone=[];
            $validatedPhone['otp_request_source']='phone';
            $validatedPhone['otp_source_value']=$validatedData['phone'];
            $phoneStatus=$this->OTPAccountVerificationsRepository->checkPhoneOTPUsed($validatedData['phone']);

            if($phoneStatus){
               throw new \Exception("Invalid Request",400);
            }
            $lastPhoneDetails=$this->getLatestUsablePhoneOTP($validatedData['phone']);
            if($lastPhoneDetails) {
                $this->OTPAccountVerificationsRepository->deleteLastUsablePhoneOTP($lastPhoneDetails);
            }
            $phoneOTP=$this->OTPAccountVerificationsRepository->generateAccountVerificationsPhoneOTP($validatedPhone);
            DB::commit();

            return $phoneOTP;
        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }
    public function verifyPhoneOTPCode($validatedData){
        DB::beginTransaction();
        try{
             $phoneOTP=$this->OTPAccountVerificationsRepository->verifyPhoneOTPCode($validatedData);
             if(!($phoneOTP)){
                 throw new \Exception('Invalid OTP :(',400);
             }
             $currentTime=Carbon::now();
             if($phoneOTP['expires_at']< $currentTime){
                 throw new \Exception('Invalid OTP :(',400);
             }
            $verified = $this->OTPAccountVerificationsRepository->updateOTPUsable($phoneOTP);
            DB::commit();

            return $verified;
        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

    public function verifyEmailOTPCode($validatedData){
        DB::beginTransaction();
        try{
            $emailOTP=$this->OTPAccountVerificationsRepository->verifyEmailOTPCode($validatedData);
            if(!($emailOTP)){
                throw new \Exception('Invalid OTP :(',400);
            }
            $currentTime=Carbon::now();

            if($currentTime > $emailOTP['expires_at']){
                throw new \Exception('Invalid OTP :(',400);
            }
            $verified = $this->OTPAccountVerificationsRepository->updateOTPUsable($emailOTP);

            DB::commit();
            return $verified;

        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
    public function generateEmailOTPVerificationsCode($validatedData){
        DB::beginTransaction();
        try{
            $validatedEmail=[];
            $validatedEmail['otp_request_source']='email';
            $validatedEmail['otp_source_value']=$validatedData['email'];
            $usedEmailOTP=$this->OTPAccountVerificationsRepository->checkEmailOTPUsed($validatedData['email']);
            if(($usedEmailOTP)){
                throw new \Exception('Invalid Request ',400);
            }
            $lastEmailDetails=$this->getLatestUsableEmailOTP($validatedData['email']);
            if($lastEmailDetails) {
                $this->OTPAccountVerificationsRepository->deleteLastUsableEmailOTP($lastEmailDetails);
            }
            $emailOTP=$this->OTPAccountVerificationsRepository->generateAccountVerificationsEmailOTP($validatedEmail);
            DB::commit();
            return $emailOTP;
        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

}
