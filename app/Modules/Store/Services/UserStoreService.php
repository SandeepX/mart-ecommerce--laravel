<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/24/2020
 * Time: 1:48 PM
 */

namespace App\Modules\Store\Services;


use App\Modules\Location\Repositories\LocationBlacklistedRepository;
use App\Modules\SalesManager\Models\ManagerStoreReferral;
use App\Modules\SalesManager\Repositories\ManagerRepository;
use App\Modules\SalesManager\Repositories\ManagerStoreReferralRepository;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\Types\Models\CompanyType;
use App\Modules\Types\Models\RegistrationType;
use App\Modules\Types\Models\StoreSize;
use App\Modules\Types\Models\StoreType;
use App\Modules\Types\Models\UserType;
use App\Modules\Types\Repositories\StoreTypeRepository;
use App\Modules\Types\Repositories\UserTypeRepository;
use App\Modules\User\Jobs\SendWelcomeEmailJob;
use App\Modules\User\Mails\WelcomeEmail;
use App\Modules\User\Models\User;
use App\Modules\User\Repositories\UserRepository;

use App\Modules\User\Services\UserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Exception;

class UserStoreService
{

    private $userRepository ,$storeRepository, $userTypeRepository,$storeTypeRepository,$userService;
    private $managerRepository,$managerStoreReferralRepository;
    private $blacklistedRepo;

    public function __construct(UserRepository $userRepository ,
                                StoreTypeRepository $storeTypeRepository,
                                UserService $userService,
                                StoreRepository $storeRepository,UserTypeRepository $userTypeRepository,
                                LocationBlacklistedRepository $blacklistedRepo,
                                ManagerRepository $managerRepository,
                                ManagerStoreReferralRepository $managerStoreReferralRepository
    ){

        $this->userRepository = $userRepository;
        $this->storeRepository = $storeRepository;
        $this->userTypeRepository = $userTypeRepository;
        $this->storeTypeRepository = $storeTypeRepository;
        $this->userService = $userService;
        $this->blacklistedRepo = $blacklistedRepo;
        $this->managerRepository = $managerRepository;
        $this->managerStoreReferralRepository = $managerStoreReferralRepository;
    }

    public function storeUserWithStore($validatedUser,$validatedStore){

        try {
            DB::beginTransaction();

            //user create
            $storeUserTypeCode = $this->userTypeRepository->findStoreUserType();
            $validatedUser['user_type_code'] = $storeUserTypeCode->user_type_code;
            $validatedUser['password'] = uniqueHash();
            // $validatedUser['password'] = $validatedUser['login_phone'];
            $user = $this->userRepository->create($validatedUser);

            //store create
            $validatedStore['user_code'] = $user->user_code;
            $validatedStore['referred_by'] = 'U00000001';
            $store = $this->storeRepository->create($validatedStore);

            //dispatching welcome mail
            $data = [
                'user' => $user,
                'login_password' => $validatedUser['password'],
                'user_type' => 'store',
                'login_link' => config('site_urls.ecommerce_site')."/store-login"
            ];

            SendWelcomeEmailJob::dispatch($user,new WelcomeEmail($data));

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return [
            'store'=>$store,
            'user' => $user,
            ];
    }

    public function storeUserWithStoreFromApi($validatedStore,$validatedUser)
    {
        try {

         DB::beginTransaction();
            //user create
            $storeUserTypeCode = $this->userTypeRepository->findStoreUserType();
            $validatedUser['user_type_code'] = $storeUserTypeCode->user_type_code;
            $validatedUser['is_first_login'] = 0;
            $validatedUser['first_login_at'] = Carbon::now();
            $user = $this->userService->storeStoreUserFromApi($validatedUser);

            //store create
            $validatedStore['user_code'] = $user->user_code;
            $manager = $this->managerRepository->findManagerCodeByReferralCode($validatedStore['referred_by']);

//            $storeTypeCode=$this->storeTypeRepository->findOrFailStoreTypeBySlug($validatedStore['store_type'])->store_type_code;
//            $validatedStore['store_type_code'] = $storeTypeCode;
            //dd($manager);

            $userCode = $user->user_code;
            $validatedStore['referred_by'] =  isset($manager) ? $manager->user_code : 'U00000001';
            $validatedStore['created_by'] = $userCode;
            $validatedStore['updated_by'] = $userCode;
            $validatedStore['store_contact_mobile'] = $user->login_phone;
            $validatedStore['store_email'] = $user->login_email;

            //for user with no prior existing store
            if($validatedStore['has_store']=='0'){
                $validatedStore['store_name'] = $user->name.' Store';
                $validatedStore['store_owner'] = $user->name;
            }
            // store in  store details table
            $store = $this->storeRepository->createFromApi($validatedStore);

            if($manager){
                //store manager and store referral
                $managerStoreReferralData = [];
                $managerStoreReferralData['manager_code'] = $manager->manager_code;
                $managerStoreReferralData['referred_store_code'] = $store->store_code;
                $managerStoreReferralData['created_by'] = $userCode;
                $managerStoreReferralData['updated_by'] = $userCode;
                $this->managerStoreReferralRepository->storeManagerStoreReferrals($managerStoreReferralData);
            }


        //    $user->sendEmailVerificationNotification();
         DB::commit();

        } catch (Exception $exception) {
         DB::rollBack();
            throw  $exception;
        }
        return [
            'store' => $store,
            'user' => $user
        ];
    }
}
