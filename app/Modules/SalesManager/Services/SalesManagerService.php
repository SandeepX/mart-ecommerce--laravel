<?php

namespace App\Modules\SalesManager\Services;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\InvestmentPlan\Helper\InvestmentPlanSubscriptionCommissionHelper;
use App\Modules\InvestmentPlan\Models\InvestmentPlanSubscription;
use App\Modules\Referrals\Traits\ReferralCodeService;
use App\Modules\SalesManager\Events\ManagerStatusApprovedEvent;
use App\Modules\SalesManager\Events\ManagerWalletTransactionEvent;
use App\Modules\SalesManager\Helpers\SalesManagerFilter;
use App\Modules\SalesManager\Models\Manager;
use App\Modules\SalesManager\Repositories\ManagerRepository;
use App\Modules\SalesManager\Repositories\ManagerStoreReferralRepository;
use App\Modules\SalesManager\Repositories\SalesManagerRegistrationStatusRepository;
use App\Modules\Store\Models\Store;
use App\Modules\Types\Repositories\UserTypeRepository;
use App\Modules\User\Models\User;
use App\Modules\User\Repositories\UserDocRepository;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\User\Services\UserDocService;
use App\Modules\Vendor\Repositories\VendorTargetRepository;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\Wallet\Classes\TransactionNotificationConfiguration;
use App\Modules\Wallet\Repositories\WalletTransactionPurposeRepository;
use App\Modules\Wallet\Services\WalletTransactionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Exception;

class SalesManagerService
{

    use ImageService,ReferralCodeService;
    private $userRepo;
    private $userRegistrationStatusRepository;
    private $vendorTargetRepo;
    private $storeRepo;
    private $docRepository;
    private $userTypeRepository;
    private $walletTransactionPurposeRepository;
    private $walletTransactionService;
    private $managerRepository;
    private $managerStoreReferralRepository;

    public function __construct(
         UserRepository $userRepo,
         SalesManagerRegistrationStatusRepository $userRegistrationStatusRepository,
         VendorTargetRepository $vendorTargetRepo,
         StoreRepository $storeRepo,
         UserDocRepository $docRepository,
         UserTypeRepository $userTypeRepository,
         WalletTransactionPurposeRepository $walletTransactionPurposeRepository,
         WalletTransactionService $walletTransactionService,
         ManagerRepository $managerRepository,
         ManagerStoreReferralRepository $managerStoreReferralRepository
    ){
        $this->userRepo = $userRepo;
        $this->userRegistrationStatusRepository = $userRegistrationStatusRepository;
        $this->vendorTargetRepo = $vendorTargetRepo;
        $this->storeRepo = $storeRepo;
        $this->docRepository = $docRepository;
        $this->userTypeRepository = $userTypeRepository;
        $this->walletTransactionPurposeRepository = $walletTransactionPurposeRepository;
        $this->walletTransactionService = $walletTransactionService;
        $this->managerRepository =$managerRepository;
        $this->managerStoreReferralRepository = $managerStoreReferralRepository;
    }
    public function getAllManagersLists($select='*',$with=[]){
        return $this->managerRepository->getAllManagersLists($select,$with);
    }

    public function findOrFailSalesManagerByUserCode($userCode){
        return $this->managerRepository->findOrFailSalesManagerByUserCode($userCode);
    }

    public function findOrFailSalesManagerByCodeWith($managerCode,$with=[]){
        return $this->managerRepository->findOrFailManagerByCode($managerCode,$with);
    }

    public function getVendorTargetsByLocationCode($userCode)
    {
        try{
            $assignedLocationDetail = $this->userRegistrationStatusRepository->getUserDetailByCode($userCode);
            $location_code = $assignedLocationDetail->assigned_area_code;
            $vendorTargetsAvaliable = $this->vendorTargetRepo->getVendorTargetBasedOnLocation($location_code);
            return $vendorTargetsAvaliable;

        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function getAllStore()
    {
        try{
           return $this->storeRepo->getAllActiveStore();
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function getStoreByReferralCode($referredBy,$paginateBy= 10)
    {
        try{
            $with = ['referredStore','referredStore.storeTypePackage'];
            return $this->managerStoreReferralRepository->with($with)->getStoreByReferralCode($referredBy,$paginateBy);
        }catch(Exception $exception){
            throw $exception;
        }
    }


    public function getReferedManagersByReferralCode($referredBy,$paginateBy= 10)
    {
        try{
            $with = ['referredManager'];
            return $this->managerStoreReferralRepository->with($with)->getReferedManagersByReferralCode($referredBy,$paginateBy);
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function getManagerDetail($userCode)
    {
        try{
            $salesManager =  $this->userRepo->findUserByCode($userCode);
            if(!$salesManager){
                throw new Exception('Sales manager not found !');
            }
            return $salesManager;
        }catch(Exception $exception){
            throw $exception;
        }
    }



    public function prepareWalletTransactionForSalesManagerStoreReferralCommission(Manager $manager,Store $store,$smsStatus=false)
    {
        try{

            if(!$manager){
                throw new Exception('User does not belongs to Sales Manager!');
            }

            $walletTransaction['wallet'] = $manager->wallet;
            $walletTransaction['wallet_transaction_purpose'] = $this->getSalesManagerStoreReferredCommissionWalletTransactionPurpose();
            $walletTransaction['amount'] = roundPrice($store->storeTypePackage->referal_registration_incentive_amount);
            $walletTransaction['remarks'] = 'Balance for store referral commission of '.$store->store_name.' ('.$store->store_code.')';
            $walletTransaction['transaction_purpose_reference_code'] = $store->store_code;
            $walletTransaction['transaction_notification_details'] = [
                'sms' => [
                    'contact_no' => $manager->manager_phone_no,
                    'status' => $smsStatus,
                    'message' => "You current account has been credited with Rs. {$walletTransaction['amount']} due to Store Referral Commission of ".$store->store_name." @ https://allpasal.com/ "
                ]
            ];

            event(new ManagerWalletTransactionEvent($walletTransaction));

        }catch (Exception $exception){
                throw  $exception;
        }


    }

    public function getSalesManagerStoreReferredCommissionWalletTransactionPurpose(){

        try{
            $slug = 'store-referred-commission';
            $userTypeCode = $this->getSalesManagerUserCode();
            $walletTransactionPurpose = $this->walletTransactionPurposeRepository
                ->findOrFailWalletTransactionPurposeBySlugAndUserTypeCode($slug,$userTypeCode);

            return $walletTransactionPurpose;

        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function getSalesManagerVisitClaimRewardWalletTransactionPurpose(){

        try{
            $slug = 'visit-claim-reward';
            $userTypeCode = $this->getSalesManagerUserCode();
            $walletTransactionPurpose = $this->walletTransactionPurposeRepository
                ->findOrFailWalletTransactionPurposeBySlugAndUserTypeCode($slug,$userTypeCode);

            return $walletTransactionPurpose;

        }catch (Exception $exception){
            throw $exception;
        }
    }



    public function prepareWalletTransactionForSalesManagerInvestmentCommission(
        Manager $referredBy,
        InvestmentPlanSubscription $investmentPlanSubscription,
        $smsStatus=false
    ){

        $investmentCommissionAmount = InvestmentPlanSubscriptionCommissionHelper::calculateInvestmentPlanSubscriptionCommission($investmentPlanSubscription);

        $walletTransaction['wallet'] = $referredBy->wallet;
        $walletTransaction['wallet_transaction_purpose'] = $this->getSalesManagerInvestmentCommissionWalletTransactionPurpose();
        $walletTransaction['amount'] = roundPrice($investmentCommissionAmount);
        $walletTransaction['remarks'] = 'Balance for Investment Commission of '.$investmentPlanSubscription->investment_holder_type.'('.$investmentPlanSubscription->investment_holder_id.')';
        $walletTransaction['transaction_purpose_reference_code'] = $investmentPlanSubscription->ip_subscription_code;
        $walletTransaction['transaction_notification_details'] = [
            'sms' => [
                'contact_no' => $referredBy->manager_phone_no,
                'status' => $smsStatus,
                'message' => "You current account has been credited with Rs. {$walletTransaction['amount']} due to Investment Commission {$investmentPlanSubscription->investment_holder_type} ({$investmentPlanSubscription->investment_holder_id})   @ https://allpasal.com/ "
            ]
        ];

        if($investmentCommissionAmount > 0){
            event(new ManagerWalletTransactionEvent($walletTransaction));
        }

    }

    public function getSalesManagerInvestmentCommissionWalletTransactionPurpose(){

        try{
            $slug = 'investment-commission';
            $userTypeCode = $this->getSalesManagerUserCode();
            $walletTransactionPurpose = $this->walletTransactionPurposeRepository
                ->findOrFailWalletTransactionPurposeBySlugAndUserTypeCode($slug,$userTypeCode);

            return $walletTransactionPurpose;

        }catch (Exception $exception){
            throw $exception;
        }
    }


    public function getSalesManagerUserCode(){
        $userType = $this->userTypeRepository->findUserTypeBySlug('sales-manager');
        if($userType){
            return $userType->user_type_code;
        }
        throw new Exception('Sales Manager User Type Not found!');
    }

    public function updateStatusByUserCode($validatedData,$managerCode){

        try{
            DB::beginTransaction();
            $manager = $this->managerRepository->findOrFailManagerByCode($managerCode);
            if($validatedData['status'] === 'approved'){
                $referralCode = $this->generateReferralCode($manager->user);
                if(SalesManagerFilter::checkManagerReferralCodeExists($referralCode,$managerCode)){
                    throw new Exception('Referral code already exists cannot approve');
                }
                $validatedData['referral_code'] = $referralCode;
                event(new ManagerStatusApprovedEvent($manager));
            }
            $validatedData['status_responded_at'] = Carbon::now();
            $manager =  $this->managerRepository->updateManagerDetail($manager,$validatedData);
            DB::commit();
            return $manager;
        }catch(Exception $exception){
            DB::rollback();
            throw $exception;
        }

    }






}
