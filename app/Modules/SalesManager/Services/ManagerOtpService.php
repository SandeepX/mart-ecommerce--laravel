<?php

namespace App\Modules\SalesManager\Services;
use App\Modules\OTP\Repositories\OTPRepository;
use App\Modules\SalesManager\Mails\ManagerEmailVerificationMail;
use App\Modules\SalesManager\Repositories\ManagerRepository;
use App\Modules\SMSProcessor\Jobs\SendSmsJob;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\User\Jobs\SendMailJob;
use App\Modules\User\Mails\AccountVerificationEmail;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\Vendor\Repositories\VendorRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class ManagerOtpService
{
    protected $otpRepository;
    protected $storeRepository;
    protected $managerRepository;
    protected $vendorRepository;
    protected $userRepository;
    public function __construct(
        OTPRepository $otpRepository,
        ManagerRepository $managerRepository,
        StoreRepository $storeRepository,
        VendorRepository $vendorRepository,
        UserRepository $userRepository
    ){
        $this->otpRepository = $otpRepository;
        $this->managerRepository = $managerRepository;
        $this->storeRepository = $storeRepository;
        $this->vendorRepository = $vendorRepository;
        $this->userRepository = $userRepository;
    }

    public function generatePhoneVerificationOTP(){
        try{
            $user = auth()->user();
            if(!$user->manager){
                 throw new Exception('user should be manager only');
            }
            if($user->phone_verified_at){
                throw new Exception('Already Phone Verified');
            }
            $validatedData = [];
            $entityDetails = $this->getEntityDetailsOfUser($user);
            $validatedData['entity'] = $entityDetails['entity'];
            $validatedData['entity_code'] = $entityDetails['entity_code'];
            $validatedData['otp_code'] = random_int(1000,9999);
            $validatedData['purpose'] = 'phone_verification';
            $validatedData['otp_request_via'] = 'phone';
            $carbonNow = Carbon::now();
            $validatedData['expires_at'] = $carbonNow->addMinutes(5);

            DB::beginTransaction();
            $this->otpRepository->updateUnUseAbleForExpiredOTP(
                $validatedData['entity'],
                $validatedData['entity_code'],
                'phone_verification'
            );
            $otp = $this->otpRepository->store($validatedData);
            $this->sendSMS($user->login_phone,$otp);
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function generateEmailVerificationOTP(){
        try{
            $user = auth()->user();
            if(!$user->manager){
                throw new Exception('user should be manager only');
            }
            if($user->email_verified_at){
                throw new Exception('Already Email Verified');
            }
            $validatedData = [];
            $entityDetails = $this->getEntityDetailsOfUser($user);
            $validatedData['entity'] = $entityDetails['entity'];
            $validatedData['entity_code'] = $entityDetails['entity_code'];
            $validatedData['otp_code'] = random_int(1000,9999);
            $validatedData['purpose'] = 'email_verification';
            $validatedData['otp_request_via'] = 'email';
            $carbonNow = Carbon::now();
            $validatedData['expires_at'] = $carbonNow->addMinutes(5);
            DB::beginTransaction();
            $this->otpRepository->updateUnUseAbleForExpiredOTP(
                $validatedData['entity'],
                $validatedData['entity_code'],
                'email_verification'
            );
            $otp = $this->otpRepository->store($validatedData);
            $this->sendMail($user,$otp);
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

    public function verifyPhoneOTP($validatedData){
        try{
            $user = auth()->user();
            if(!$user->manager){
                throw new Exception('user should be manager only');
            }
            if($user->phone_verified_at){
                throw new Exception('Already Phone Verified');
            }
            $entityDetails = $this->getEntityDetailsOfUser($user);
            $latestOTP = $this->otpRepository->getLatestUseAbleOTPForVerification(
                $entityDetails['entity'],
                $entityDetails['entity_code'],
                'phone_verification'
            );

            if(!$latestOTP){
                throw new Exception('Invalid OTP :(');
            }

//            if($latestOTP->purpose != 'phone_verification'){
//                throw new Exception('Invalid OTP :(');
//            }

            if($latestOTP->expires_at < Carbon::now()){
                throw new Exception('Invalid OTP :(');
            }

            if($latestOTP->otp_code != $validatedData['otp_code']){
                throw new Exception('Invalid OTP :(');
            }
            DB::beginTransaction();

            $this->userRepository->updatePhoneVerificationStatus($user);

            $this->otpRepository->updateUnUseAbleForVerifiedOTP($latestOTP);
            $entityFunction = 'update'.ucfirst($latestOTP->entity).'Entity';
            if(method_exists($this,$entityFunction)){
                $this->{$entityFunction}($user,$latestOTP);
            }
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

    public function verifyEmailOTP($validatedData){
        try{
            $user = auth()->user();
            if(!$user->manager){
                throw new Exception('user should be manager only');
            }
            if($user->email_verified_at){
                throw new Exception('Already Email Verified');
            }
            $entityDetails = $this->getEntityDetailsOfUser($user);
            $latestOTP = $this->otpRepository->getLatestUseAbleOTPForVerification(
                $entityDetails['entity'],
                $entityDetails['entity_code'],
                'email_verification'
            );

            if(!$latestOTP){
                throw new Exception('Invalid OTP :(');
            }
//
//            if($latestOTP->purpose != 'email_verification'){
//                throw new Exception('Invalid OTP :(');
//            }
            if($latestOTP->expires_at < Carbon::now()){
                throw new Exception('Invalid OTP :(');
            }
            if($latestOTP->otp_code != $validatedData['otp_code']){
                throw new Exception('Invalid OTP :(');
            }

            DB::beginTransaction();
            $this->userRepository->updateEmailVerificationStatus($user);
            $this->otpRepository->updateUnUseAbleForVerifiedOTP($latestOTP);
            $entityFunction = 'update'.ucfirst($latestOTP->entity).'Entity';
            if(method_exists($this,$entityFunction)){
                $this->{$entityFunction}($user,$latestOTP);
            }
            DB::commit();
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

    private function updateManagerEntity($user,$latestOTP){
        $manager = $user->manager;
        if($latestOTP->otp_request_via =='phone'){
            $this->managerRepository->updatePhoneVerificationStatus($manager);
        }
        if($latestOTP->otp_request_via == 'email'){
            $this->managerRepository->updateEmailVerificationStatus($manager);
        }
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
        SendMailJob::dispatch($user->login_email,new ManagerEmailVerificationMail($data));
    }

}
