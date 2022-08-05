<?php

namespace App\Modules\User\Services;
use App\Modules\OTP\Repositories\OTPRepository;
use App\Modules\SalesManager\Repositories\ManagerRepository;
use App\Modules\SMSProcessor\Jobs\SendSmsJob;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\User\Jobs\SendAccountVerificationEmailJob;
use App\Modules\User\Jobs\SendMailJob;
use App\Modules\User\Mails\AccountVerificationEmail;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\Vendor\Repositories\VendorRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRegistrationOTPService
{
    private $userRepository;
    private $otpRepository;
    private $storeRepository;
    private $managerRepository;
    private $vendorRepository;
    public function __construct(
        UserRepository $userRepository,
        OTPRepository $otpRepository,
        ManagerRepository $managerRepository,
        StoreRepository $storeRepository,
        VendorRepository $vendorRepository
    ){
        $this->userRepository = $userRepository;
        $this->otpRepository = $otpRepository;
        $this->storeRepository = $storeRepository;
        $this->managerRepository = $managerRepository;
        $this->vendorRepository = $vendorRepository;
    }

    public function createOTP($validatedData){
        try{
            //dd($validatedData);
            $user = $this->userRepository->findOrFailUserByEmail($validatedData['email']);
            if(!$user){
                throw new Exception('Invalid User :(');
            }
            if($validatedData['otp_request_via']=='phone' && $user->phone_verified_at){
               throw new Exception('Already Phone Verified');
            }
            if($validatedData['otp_request_via']=='email' && $user->email_verified_at){
                throw new Exception('Already Email Verified');
            }
            $dataToSave = [];
            $entityDetails = $this->getEntityDetailsOfUser($user);
            $dataToSave['entity'] = $entityDetails['entity'];
            $dataToSave['entity_code'] = $entityDetails['entity_code'];
            $dataToSave['otp_code'] = random_int(1000,9999);
            $dataToSave['purpose'] = 'account_registration';
            $dataToSave['otp_request_via'] = $validatedData['otp_request_via'];
            $carbonNow = Carbon::now();
            $dataToSave['expires_at'] = $carbonNow->addMinutes(5);
            DB::beginTransaction();
            $this->otpRepository->updateUnUseAbleForExpiredOTP(
                $dataToSave['entity'],
                $dataToSave['entity_code'],
                'account_registration'
            );
            $otp = $this->otpRepository->store($dataToSave);

            if($otp->otp_request_via == 'phone'){
                $this->sendSMS($user->login_phone,$otp);
            }

            if($otp->otp_request_via == 'email'){
                $this->sendMail($user,$otp);
            }

            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
           throw $exception;
        }
    }

    public function verifyOTP($validatedData){
        try{
            $user = $this->userRepository->findOrFailUserByEmail($validatedData['email']);
            if(!$user){
                throw new Exception('Invalid User :(');
            }

            $entityDetails = $this->getEntityDetailsOfUser($user);
            $latestOTP = $this->otpRepository->getLatestUseAbleOTPForVerification(
                    $entityDetails['entity'],
                    $entityDetails['entity_code'],
                    'account_registration'
            );

            if(!$latestOTP){
              throw new Exception('Invalid OTP :(');
            }
//            if($latestOTP->purpose != 'account_verification'){
//                throw new Exception('Invalid OTP :(');
//            }
            if($latestOTP->expires_at < Carbon::now()){
               throw new Exception('Invalid OTP :(');
            }
            if($latestOTP->otp_request_via =='phone' && $user->phone_verified_at){
                throw new Exception('Already Phone Verified');
            }
            if($latestOTP->otp_request_via =='email' && $user->email_verified_at){
                throw new Exception('Already Email Verified');
            }
            if($latestOTP->otp_code != $validatedData['otp_code']){
               throw new Exception('Invalid OTP :(');
            }
            DB::beginTransaction();
            if($latestOTP->otp_request_via == 'phone'){
                $this->userRepository->updatePhoneVerificationStatus($user);
            }
            if($latestOTP->otp_request_via == 'email'){
                $this->userRepository->updateEmailVerificationStatus($user);
            }

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

    private function updateStoreEntity($user,$latestOTP){
        $store = $user->store;
        if($latestOTP->otp_request_via =='phone'){
            $this->storeRepository->updatePhoneVerificationStatus($store);
        }
        if($latestOTP->otp_request_via == 'email'){
            $this->storeRepository->updateEmailVerificationStatus($store);
        }
    }

    private function updateVendorEntity($user,$latestOTP){
        $vendor = $user->vendor;
        if($latestOTP->otp_request_via =='phone'){
            $this->vendorRepository->updatePhoneVerificationStatus($vendor);
        }
        if($latestOTP->otp_request_via == 'email'){
           $this->vendorRepository->updateEmailVerificationStatus($vendor);
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
        SendMailJob::dispatch($user->login_email,new AccountVerificationEmail($data));
    }

}
