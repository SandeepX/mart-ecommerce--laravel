<?php

namespace App\Modules\SalesManager\Services;

use App\Modules\Application\Classes\EmailValidator;
use App\Modules\Application\Classes\PhoneNumberValidator;
use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Location\Traits\LocationHelper;
use App\Modules\OTP\Services\OTPAccountVerificationsService;
use App\Modules\Referrals\Traits\ReferralCodeService;
use App\Modules\SalesManager\Repositories\ManagerDocRepository;
use App\Modules\SalesManager\Repositories\ManagerRepository;
use App\Modules\SalesManager\Repositories\ManagerToManagerReferralRepository;
use App\Modules\Types\Repositories\UserTypeRepository;
use App\Modules\User\Models\User;
use App\Modules\User\Repositories\UserDocRepository;
use App\Modules\SalesManager\Repositories\SalesManagerRegistrationStatusRepository;
use App\Modules\User\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Exception;

class UserSalesManagerService
{
    use ReferralCodeService;
    use ImageService;
    use LocationHelper;

    private $salesManagerRepository;
    private $userTypeRepository;
    private $userRepo;
    private $userDocRepo;
    private $salesManagerRegistrationStatusRepository;
    private $managerRepository;
    private $mangerDocRepository;
    private $managerToManagerReferralRepository;
    private $OTPAccountVerificationsService;

    public function __construct(UserRepository $userRepo,
                                UserTypeRepository $userTypeRepository,
                                UserDocRepository $userDocRepo,
                                SalesManagerRegistrationStatusRepository $salesManagerRegistrationStatusRepository,
                                ManagerRepository $managerRepository,
                                ManagerDocRepository $managerDocRepository,
                                ManagerToManagerReferralRepository $managerToManagerReferralRepository,
                                OTPAccountVerificationsService $OTPAccountVerificationsService
    ){
        $this->userRepo = $userRepo;
        $this->userTypeRepository = $userTypeRepository;
        $this->userDocRepo = $userDocRepo;
        $this->salesManagerRegistrationStatusRepository = $salesManagerRegistrationStatusRepository;
        $this->managerRepository = $managerRepository;
        $this->managerDocRepository = $managerDocRepository;
        $this->managerToManagerReferralRepository = $managerToManagerReferralRepository;
        $this->OTPAccountVerificationsService = $OTPAccountVerificationsService;
    }

    public function storeUserSalesManager($validatedSalesManagerData, $validatedUserDocData)
    {
        $avatarNameToStore = '';
        try {
            DB::beginTransaction();

           // PhoneNumberValidator::validatePhoneNumber($validatedSalesManagerData['login_phone']);
           // EmailValidator::validateEmail($validatedSalesManagerData['login_email']);

            $phoneOTPData=[];
            if($validatedSalesManagerData['phone_otp_code']){
                $phoneOTPData['phone'] = $validatedSalesManagerData['login_phone'];
                $phoneOTPData['otp_code'] = $validatedSalesManagerData['phone_otp_code'];
                $verifiedPhone = $this->OTPAccountVerificationsService->verifyPhoneOTPCode($phoneOTPData);
            }
//            $emailOTPData=[];
//            if($validatedSalesManagerData['email_otp_code']){
//                $emailOTPData['email']=$validatedSalesManagerData['login_email'];
//                $emailOTPData['otp_code']=$validatedSalesManagerData['email_otp_code'];
//                $verifiedEmail = $this->OTPAccountVerificationsService->verifyEmailOTPCode($emailOTPData);
//            }
            //create user
            $salesManagerUserType = $this->userTypeRepository->findSalesManagerUserType();
            $validatedSalesManagerData['user_type_code'] = $salesManagerUserType->user_type_code;

            $referralByManager = NULL;
            if(isset($validatedSalesManagerData['referral_code'])){
                $referralByManager = $this->managerRepository->findManagerCodeByReferralCode($validatedSalesManagerData['referral_code']);
            }

            if (isset($validatedSalesManagerData['avatar'])) {
                $avatarNameToStore = $this->storeImageInServer($validatedSalesManagerData['avatar'], User::AVATAR_UPLOAD_PATH);
                $validatedSalesManagerData['avatar'] = $avatarNameToStore;
            }

            $user = $this->userRepo->createFromApi($validatedSalesManagerData);

            $validatedManagerDetailData = [];
            $validatedManagerDetailData['manager_name'] = $user->name;
            $validatedManagerDetailData['manager_email'] = $user->login_email;
            $validatedManagerDetailData['manager_phone_no'] = $user->login_phone;
            $validatedManagerDetailData['has_two_wheeler_license'] = $validatedSalesManagerData['has_two_wheeler_license'];
            $validatedManagerDetailData['has_four_wheeler_license'] = $validatedSalesManagerData['has_four_wheeler_license'];
            $validatedManagerDetailData['temporary_ward_code'] =  $validatedSalesManagerData['temporary_ward'];
            $validatedManagerDetailData['permanent_ward_code'] =  $validatedSalesManagerData['ward_code'];
            $validatedManagerDetailData['user_code'] = $user->user_code;
            $validatedManagerDetailData['status_responded_at'] = Carbon::now();
            $manager = $this->managerRepository->storeManagerDetail($validatedManagerDetailData);


            //generate referrral code for sales manager
              $validatedManagerUpdateDetailData = [];
              $validatedManagerUpdateDetailData['temporary_full_location'] = $this->getFullLocationPathByLocation($manager->temporaryLocation);
              $validatedManagerUpdateDetailData['permanent_full_location'] = $this->getFullLocationPathByLocation($manager->ward);
              $this->managerRepository->updateManagerDetail($manager,$validatedManagerUpdateDetailData);


             $validatedUserDocData['doc_name'] = array_shift($validatedUserDocData['doc_name']);
             $validatedUserDocData['doc_number'] = array_shift($validatedUserDocData['doc_number']);
             $validatedUserDocData['doc_issued_district'] = isset($validatedUserDocData['doc_issued_district']) ? $validatedUserDocData['doc_issued_district'] : null;


            //only citizen is sent from frontend with 0 index always front and 1 index always back
            if ($validatedUserDocData['doc_name'] == 'citizenship') {
                if(count($validatedUserDocData['doc']) != 2){
                    throw new Exception('Citizenship front and back images are required',400);
                }
                $validatedUserDocData['doc_name'] = [];
                array_push($validatedUserDocData['doc_name'], 'citizenship-front');
                array_push($validatedUserDocData['doc_name'], 'citizenship-back');
            } else {
                $validatedUserDocData['doc_name'] = explode(' ', $validatedUserDocData['doc_name']);
            }

            //create user doc
            foreach ($validatedUserDocData['doc'] as $key => $validatedDoc) {
                $this->managerDocRepository->storeManagerDocument([
                    'manager_code' => $manager->manager_code,
                    'doc_name' => $validatedUserDocData['doc_name'][$key],
                    'doc_number' => $validatedUserDocData['doc_number'],
                    'doc_file' => $validatedDoc,
                    'doc_issued_district' => $validatedUserDocData['doc_issued_district'],
                ]);
            }

            if($referralByManager){
                //store Referred manager details
                $managerToManagerReferredData = [];
                $managerToManagerReferredData['manager_code'] = $referralByManager->manager_code;
                $managerToManagerReferredData['referred_manager_code'] = $manager->manager_code;
                $managerToManagerReferredData['created_by'] = $user->user_code;
                $managerToManagerReferredData['updated_by'] = $user->user_code;
                $this->managerToManagerReferralRepository->createManagerToManagerReferrals($managerToManagerReferredData);
            }
            //manager otp verifications
            if(isset($verifiedPhone)){
                $this->userRepo->updatePhoneVerificationStatus($manager->user);
                $this->managerRepository->updatePhoneVerificationStatus($manager);
            }

//            if(isset($verifiedEmail)){
//                $this->userRepo->updateEmailVerificationStatus($manager->user);
//                $this->managerRepository->updateEmailVerificationStatus($manager);
//            }

          //  $user->sendEmailVerificationNotification();
            DB::commit();
            return $user;
        } catch (Exception $exception) {
            $this->deleteImageFromServer(User::AVATAR_UPLOAD_PATH, $avatarNameToStore);
            DB::rollBack();
            throw $exception;
        }

    }

    public function updateSalesManagerProfile($validatedSalesManagerData,$validatedUserDocData,$managerDetail,$userDetail)
    {
        $updatedAvatarNameToStore = '';
        try{
            DB::beginTransaction();
            //dd($validatedSalesManagerData,$validatedUserDocData);

            if(isset($validatedSalesManagerData['avatar']) && !is_null($managerDetail['avatar'])){
                $this->deleteImageFromServer(User::AVATAR_UPLOAD_PATH, $managerDetail['avatar']);
                $updatedAvatarNameToStore = $this->storeImageInServer($validatedSalesManagerData['avatar'], User::AVATAR_UPLOAD_PATH);
                $validatedSalesManagerData['avatar'] = $updatedAvatarNameToStore;
            }
            if(isset($validatedSalesManagerData['avatar']) && is_null($managerDetail['avatar'])){
                $updatedAvatarNameToStore = $this->storeImageInServer($validatedSalesManagerData['avatar'], User::AVATAR_UPLOAD_PATH);
                $validatedSalesManagerData['avatar'] = $updatedAvatarNameToStore;
            }

            $validatedManagerDetailData = [];
            if(array_key_exists('name',$validatedSalesManagerData)){
                $validatedManagerDetailData['manager_name'] = $validatedSalesManagerData['name'];
            }
            if(array_key_exists('has_two_wheeler_license',$validatedSalesManagerData)){
                $validatedManagerDetailData['has_two_wheeler_license'] = $validatedSalesManagerData['has_two_wheeler_license'];
            }
            if(array_key_exists('has_two_wheeler_license',$validatedSalesManagerData)){
                $validatedManagerDetailData['has_two_wheeler_license'] = $validatedSalesManagerData['has_two_wheeler_license'];
            }
            if(array_key_exists('temporary_ward',$validatedSalesManagerData)){
                $validatedManagerDetailData['temporary_ward_code'] =  $validatedSalesManagerData['temporary_ward'];
            }
            if(array_key_exists('ward_code',$validatedSalesManagerData)){
                $validatedManagerDetailData['permanent_ward_code'] =  $validatedSalesManagerData['ward_code'];
            }
            $updatedUserProfile = $this->userRepo->update($validatedSalesManagerData,$userDetail);
            $updatedManagerProfile = $this->managerRepository->updateManagerDetail($managerDetail,$validatedManagerDetailData);

            //update full location of managers
            if(array_key_exists('temporary_ward',$validatedSalesManagerData)
                ||
                array_key_exists('ward_code',$validatedSalesManagerData)) {
                $validatedManagerUpdateDetailData = [];
                $validatedManagerUpdateDetailData['temporary_full_location'] = $this->getFullLocationPathByLocation($updatedManagerProfile->temporaryLocation);
                $validatedManagerUpdateDetailData['permanent_full_location'] = $this->getFullLocationPathByLocation($updatedManagerProfile->ward);
                $this->managerRepository->updateManagerDetail($updatedManagerProfile, $validatedManagerUpdateDetailData);
            }
           // dd($updatedUserProfile);

            if(isset($validatedUserDocData['doc_name']) && count($validatedUserDocData['doc_name']) > 0){
                $validatedUserDocData['doc_name'] = array_shift($validatedUserDocData['doc_name']);
                $validatedUserDocData['doc_number'] = array_shift($validatedUserDocData['doc_number']);
                $validatedUserDocData['doc_issued_district'] = isset($validatedUserDocData['doc_issued_district']) ? $validatedUserDocData['doc_issued_district'] : null;

                //only citizen is sent from frontend with 0 index always front and 1 index always back
                if ($validatedUserDocData['doc_name'] == 'citizenship') {
                    if(count($validatedUserDocData['doc']) != 2){
                       throw new Exception('Citizenship front and back images are required',400);
                    }
                    $validatedUserDocData['doc_name'] = [];
                    array_push($validatedUserDocData['doc_name'], 'citizenship-front');
                    array_push($validatedUserDocData['doc_name'], 'citizenship-back');
                } else {
                    $validatedUserDocData['doc_name'] = explode(' ', $validatedUserDocData['doc_name']);
                }
                       // dd($validatedUserDocData['doc_name']);
                foreach($validatedUserDocData['doc_name'] as $key => $docName){

                    //dd($docName);
                    $getUserDocName = $this->managerDocRepository->getManagerDocByDocName($docName);

                    if(($getUserDocName)) {
                            $managerDocs = $this->managerDocRepository->updateDocument([
                                'manager_code' => $updatedManagerProfile->manager_code,
                                'doc_name' => $getUserDocName['doc_name'],
                                'doc_number' => $validatedUserDocData['doc_number'],
                                'doc_file' => $validatedUserDocData['doc'][$key],
                                'doc_issued_district' => $validatedUserDocData['doc_issued_district'],
                            ],$getUserDocName);

                    }else{
                        $managerDocs = $this->managerDocRepository->storeManagerDocument([
                            'manager_code' => $updatedManagerProfile->manager_code,
                            'doc_name' => $validatedUserDocData['doc_name'][$key],
                            'doc_number' => $validatedUserDocData['doc_number'],
                            'doc_file' => $validatedUserDocData['doc'][$key],
                            'doc_issued_district' => $validatedUserDocData['doc_issued_district'],
                        ]);
                    }
                }
            }

            DB::commit();
            return $updatedUserProfile;
        } catch (Exception $exception) {
            $this->deleteImageFromServer(User::AVATAR_UPLOAD_PATH, $updatedAvatarNameToStore);
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateUserSalesManagerEmail($userCode,$validatedManagerData){
        try{

            $with = ['manager'];
            $user = $this->userRepo->findOrFailUserByCode($userCode,$with);

            if($user->login_email === $validatedManagerData['email']){
                throw new Exception('this is already existed email of yours');
            }

            $validatedUserData = [];
            $validatedUserData['login_email'] = $validatedManagerData['email'];
            $validatedUserData['email_verified_at'] = NULL;
            $validatedManagerData['manager_email'] =  $validatedManagerData['email'];
            $validatedManagerData['email_verified_at'] = NULL;

            DB::beginTransaction();
            $this->userRepo->update($validatedUserData,$user);
            $manager =  $this->managerRepository->updateManagerDetail($user->manager,$validatedManagerData);
            DB::commit();
            return $manager;
        }catch (Exception  $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateUserSalesManagerPhone($userCode,$validatedManagerData){
        try{
                $with = ['manager'];
                $user = $this->userRepo->findOrFailUserByCode($userCode,$with);
                if($user->login_phone === $validatedManagerData['phone']){
                    throw new Exception('this is already existed phone of yours');
                }
                $validatedUserData = [];
                $validatedUserData['login_phone'] = $validatedManagerData['phone'];
                $validatedUserData['phone_verified_at'] = NULL;
                $validatedManagerData['manager_phone_no'] = $validatedManagerData['phone'];
                $validatedManagerData['phone_verified_at'] = NULL;
                DB::beginTransaction();
                $this->userRepo->update($validatedUserData,$user);
                $manager = $this->managerRepository->updateManagerDetail($user->manager,$validatedManagerData);
                DB::commit();
                return $manager;
        }catch (Exception  $exception){
            DB::rollBack();
            throw $exception;
        }
    }


}
