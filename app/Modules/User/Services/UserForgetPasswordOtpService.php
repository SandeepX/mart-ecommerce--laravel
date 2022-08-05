<?php

namespace App\Modules\User\Services;

use App\Modules\OTP\Repositories\OTPRepository;
use App\Modules\SalesManager\Mails\EmailVerificationMail;
use App\Modules\SMSProcessor\Jobs\SendSmsJob;
use App\Modules\User\Jobs\SendMailJob;
use App\Modules\User\Mails\UserForgotPasswordEmail;
use App\Modules\User\Repositories\UserRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class UserForgetPasswordOtpService
{
    private $userRepository;
    private $otpRepository;
    public function __construct(
        UserRepository $userRepository,
        OTPRepository $otpRepository
    ){
        $this->userRepository = $userRepository;
        $this->otpRepository = $otpRepository;
    }

    public function createOTP($validatedData){
        try{
            if($validatedData['otp_request_via'] == 'phone'){
               $user = $this->userRepository->findOrFailUserByPhone($validatedData['phone']);
            }else{
               $user = $this->userRepository->findOrFailUserByEmail($validatedData['email']);
            }

            $entityDetails = $this->getEntityDetailsOfUser($user);
            $validatedData['entity'] = $entityDetails['entity'];
            $validatedData['entity_code'] = $entityDetails['entity_code'];
            $validatedData['otp_code'] = random_int(1000,9999);
            $validatedData['purpose'] = 'forgot_password';
            $carbonNow = Carbon::now();
            $validatedData['expires_at'] = $carbonNow->addMinutes(5);

            DB::beginTransaction();
            $this->otpRepository->updateUnUseAbleForExpiredOTP(
                        $validatedData['entity'],
                        $validatedData['entity_code'],
                'forgot_password');
            $otp = $this->otpRepository->store($validatedData);

           // dd($otp);

            if($otp->otp_request_via == 'phone'){
                $this->sendSMS($user->login_phone,$otp);
            }else{
                $this->sendMail($user,$otp);
            }
            DB::commit();
            return $otp;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function verifyOTP($validatedData){
        try{
            if($validatedData['otp_request_via'] == 'phone'){
                $user = $this->userRepository->findOrFailUserByPhone($validatedData['phone']);
            }else{
                $user = $this->userRepository->findOrFailUserByEmail($validatedData['email']);
            }

            $entityDetails = $this->getEntityDetailsOfUser($user);
            $latestOTP = $this->otpRepository->getLatestUseAbleOTPForVerification(
                $entityDetails['entity'],
                $entityDetails['entity_code'],
                'forgot_password'
            );

            if(!$latestOTP){
                throw new Exception('Invalid OTP :(');
            }


            if($latestOTP->purpose_verified != 0){
                throw new Exception('Invalid OTP :(');
            }

            if($latestOTP->expires_at < Carbon::now()){
                throw new Exception('Invalid OTP :(');
            }

            if($latestOTP->otp_code != $validatedData['otp_code']){
                throw new Exception('Invalid OTP :(');
            }

             DB::beginTransaction();
             $latestOTP =  $this->otpRepository->verifyOTPPurpose($latestOTP);
             DB::commit();
             return $latestOTP;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    private function getEntityDetailsOfUser($user){
        $userTypeNameSpace = $user->userType->namespace;
        $data = [];
        $data['entity'] = strtolower(substr($userTypeNameSpace,(strrpos($userTypeNameSpace,'\\') + 1)));
        switch($data['entity']){
            case 'store':
                $data['entity_code'] = $user->store->store_code;
                break;
            case 'manager':
                $data['entity_code'] = $user->manager->manager_code;
                break;
            case 'vendor':
                $data['entity_code'] = $user->vendor->vendor_code;
                break;
            default:
                $data['entity_code'] = $user->user_code;
                break;
        }
        return $data;
    }

    private function sendSMS($phoneNumber,$otpData)
    {
        $data['purpose'] = 'OTP verification';
        $data['purpose_code'] = $otpData->id;
        return SendSmsJob::dispatch(
            $phoneNumber,
            'Verification code   '.$otpData->otp_code. ' -@ https://allpasal.com/',
            $data
        );
    }

    private function sendMail($user,$otpData){
        $data =  [];
        $data['name'] = $user->name;
        $data['user_type'] = $user->userType->user_type_name;
        $data['otp'] = $otpData->otp_code;
        SendMailJob::dispatch($user->login_email,new UserForgotPasswordEmail($data));
    }


}
