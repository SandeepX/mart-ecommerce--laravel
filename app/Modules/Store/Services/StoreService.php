<?php

namespace App\Modules\Store\Services;

use App\Modules\AlpasalWarehouse\Repositories\WarehouseRepository;
use App\Modules\Application\Classes\EmailValidator;
use App\Modules\Application\Classes\PhoneNumberValidator;
use App\Modules\Location\Repositories\LocationBlacklistedRepository;
use App\Modules\OTP\Services\OTPAccountVerificationsService;
use App\Modules\SalesManager\Services\SalesManagerService;
use App\Modules\Store\Events\StoreRegisteredEvent;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\Store\Repositories\StoreWarehouseRepository;
use App\Modules\User\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

use Exception;

class StoreService
{
    private $storeRepository,$userStoreService,$warehouseRepository,$storeWarehouseRepository,$storeWarehouseService;

    private $salesManagerService;
    private $blacklistedRepo;
    private $OTPAccountVerificationsService;
    private $userRepository;

    public function __construct(
        UserRepository $userRepository,
        StoreRepository $storeRepository,
        UserStoreService $userStoreService,
        WarehouseRepository $warehouseRepository,
        StoreWarehouseRepository $storeWarehouseRepository,
        StoreWarehouseService $storeWarehouseService,
        SalesManagerService $salesManagerService,
        OTPAccountVerificationsService $OTPAccountVerificationsService,
        LocationBlacklistedRepository $blacklistedRepo
    ){
        $this->userRepository=$userRepository;
       $this->storeRepository = $storeRepository;
       $this->userStoreService= $userStoreService;
       $this->storeWarehouseRepository = $storeWarehouseRepository;
       $this->warehouseRepository= $warehouseRepository;
       $this->storeWarehouseService= $storeWarehouseService;
       $this->salesManagerService = $salesManagerService;
       $this->blacklistedRepo = $blacklistedRepo;
       $this->OTPAccountVerificationsService=$OTPAccountVerificationsService;

    }

    public function getAllStores(){
        return $this->storeRepository->getAllStores();
    }
    public function getAllStoresWith(array $with){
        return $this->storeRepository->getAllStores($with);
    }

    public function getAllActiveStores($with=[],$select=[]){
        return $this->storeRepository
            ->select($select)->getAllStoresByActiveStatus(true,$with);
    }

    public function getStoresHavingWarehouses(array $with){
        return $this->storeRepository->getStoresHavingWarehouses($with);
    }

    public function findStoreByCode($StoreCode){
        return $this->storeRepository->findStoreByCode($StoreCode);
    }


    public function findStoreBySlug($StoreSlug){
        return $this->storeRepository->findStoreBySlug($StoreSlug);
    }

    public function findOrFailStoreByCode($StoreCode)
    {
        return $this->storeRepository->findOrFailStoreByCode($StoreCode);
    }

    public function findOrFailStoreByCodeWith($StoreCode,array $with=[],$select='*')
    {
        return $this->storeRepository->findOrFailStoreByCode($StoreCode,$with,$select);
    }

    public function findOrFailStoreBySlug($StoreSlug)
    {
        return $this->storeRepository->findOrFailStoreBySlug($StoreSlug);
    }

    public function getStoreUserCodeByStoreCode($storeCode){
        return $this->storeRepository->getUserCodeByStoreCode($storeCode);
    }

    public function findStoreForSupportAdminByFormData($filterParameterData)
    {
       try{
           $store = $this->storeRepository->findStoreDetailForSupportAdmin($filterParameterData);
           $store = collect($store);
           if($store->isEmpty()){
               throw new Exception('Sorry, Store with given form data not found');
           }
           return $store;
       }catch(Exception $exception){
           throw $exception;
       }
    }


    public function createStore($validatedStore){
        DB::beginTransaction();
        try {

            $store = $this->storeRepository->create($validatedStore);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $store;
    }

    public function createStoreWithUser($validatedUser,$validatedStore){
        try{
           return $this->userStoreService->storeUserWithStore($validatedUser,$validatedStore);
        }catch (Exception $exception){
            throw $exception;
        }
    }


    public function updateStore($validatedStore, $storeCode)
    {
        DB::beginTransaction();

        try {
            $store = $this->storeRepository->findOrFailStoreByCode($storeCode);

            if(!empty($validatedStore['pan_vat_no']))
            {
                $validatedStore['has_store'] = 1;
            }
            if(!empty($store->pan_vat_no && $store->has_store === 1))
            {
                $validatedStore['has_store'] = 1;
            }

            $store = $this->storeRepository->update($validatedStore,$store);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $store;
    }

    public function deleteStore($storeCode)
    {
        DB::beginTransaction();
        try {
            $store = $this->storeRepository->findOrFailStoreByCode($storeCode);
            $store = $this->storeRepository->delete($store);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $store;
    }

    public function syncStoreWarehouses($validated)
    {
        try {
            $status = "approved";
            DB::beginTransaction();
            $store = $this->storeRepository->findOrFailStoreByCode($validated['store_code']);

            if($store->status == !$status)
            {
                throw new Exception('The store is not approved to connect warehouse');
            }
            if(isset($store->warehouses) && count($store->warehouses) > 1 ){
                throw new Exception('You have already connected your store with the warehouse');
            }
            //$store = $this->storeRepository->syncStoreWarehouses($store, $validated['warehouse_codes']);
           // dd($validated['warehouse_codes']);
            $newValidatedWarehouseCodes=[];
            foreach ($validated['warehouse_codes'] as $warehouseCode){
                $newValidatedWarehouseCodes[$warehouseCode]=[
                        'connection_status' => 1
                ];
            }

           // dd($newValidatedWarehouseCodes);
            $store = $this->storeWarehouseRepository->syncStoreWithWarehouses($store, $newValidatedWarehouseCodes);
            DB::commit();
            return $store;

        } catch (Exception $exception) {
            DB::rollBack();
            throw($exception);
        }
    }

    public function toggleWarehouseStoreConnection($storeCode,$warehouseCode){
        try{
            $status = "approved";
            $store = $this->storeRepository->findOrFailStoreByCode($storeCode);
            if($store->status == !$status)
            {
                throw new Exception('The store is not approved to change connection status');
            }
            $warehouse= $this->warehouseRepository->findOrFailByCode($warehouseCode);

            DB::beginTransaction();
            $this->storeWarehouseService->toggleWarehouseStoreConnection($store,$warehouse);
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }


    public function updateStatus($storeCode,$validateData)
    {

        try {
            $storeStatuses = [
                'accept' => 'processing',
                'reject'=>'rejected',
            ];

            $status = $validateData['store_status'];
            if(!in_array($status,array_keys($storeStatuses))){
                throw new Exception('Invalid Status Update');
            }

            $store = $this->findOrFailStoreByCode($storeCode);

            $store = $this->storeRepository->updateStatus($store,[
                'status' => $storeStatuses[$status],
                'remarks' => $validateData['remarks'],
            ]);

            if($store->status == 'processing') {

                event(new StoreRegisteredEvent($store));
                if (($storeTypePackage = $store->storeTypePackage)) {
                    $storeNonRefundableRegCharge = $storeTypePackage->non_refundable_registration_charge;
                    $storeRefundableRegCharge = $storeTypePackage->refundable_registration_charge;
                    $storeBaseInvestmentCharge = $storeTypePackage->base_investment;
                    $referralRegistrationIncentiveAmount = $storeTypePackage->referal_registration_incentive_amount;
                    if (
                        $storeNonRefundableRegCharge == 0
                        && $storeRefundableRegCharge == 0
                    ) {
                        $this->storeRepository->changeStoreStatusToApproved($store);
                    }
//                    if (
//                        $store->status == 'approved'
//                        && $store->has_purchase_power == 0
//                        && $storeBaseInvestmentCharge == 0
//                    ) {
//                        if($store->referredBy->isSalesManagerUser() && $referralRegistrationIncentiveAmount>0){
//                            $this->salesManagerService->prepareWalletTransactionForSalesManagerStoreReferralCommission(
//                                $store->referredBy,
//                                $store
//                            );
//                        }
//                        $this->storeRepository->enablePurchasingPower($store);
//                    }
                }
            }

            return $store;
        } catch (Exception $exception) {
            throw  $exception;
        }
    }



   // public function


    public function createUserWithStoreFromApi($validatedStore,$validatedUser)
    {
        DB::beginTransaction();
        try{
            $phoneOTPData=[];

         //   PhoneNumberValidator::validatePhoneNumber($validatedUser['login_phone']);
           // EmailValidator::validateEmail($validatedUser['login_email']);

            if($validatedStore['phone_otp_code']){
                $phoneOTPData['phone']=$validatedUser['login_phone'];
                $phoneOTPData['otp_code']=$validatedStore['phone_otp_code'];
                $verifiedPhone = $this->OTPAccountVerificationsService->verifyPhoneOTPCode($phoneOTPData);
            }
//            $emailOTPData=[];
//            if($validatedStore['email_otp_code']){
//                $emailOTPData['email']=$validatedUser['login_email'];
//                $emailOTPData['otp_code']=$validatedStore['email_otp_code'];
//                $verifiedEmail = $this->OTPAccountVerificationsService->verifyEmailOTPCode($emailOTPData);
//            }
            $details = $this->userStoreService->storeUserWithStoreFromApi($validatedStore,$validatedUser);
            $blacklistedLocation = $this->blacklistedRepo->getBlackListedLocationByLocationCode($validatedStore['store_location_code']);

            if(!$blacklistedLocation){
                $this->autoUpdateStoreStatusWhileRegistration($details['store']['store_code']);
            }

            if(isset($verifiedPhone)){
                $this->userRepository->updatePhoneVerificationStatus($details['user']);
                $this->storeRepository->updatePhoneVerificationStatus($details['store']);
            }

//            if(isset($verifiedEmail)){
//                $this->userRepository->updateEmailVerificationStatus($details['user']);
//                $this->storeRepository->updateEmailVerificationStatus($details['store']);
//            }

            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    private function autoUpdateStoreStatusWhileRegistration($storeCode)
    {
        DB::beginTransaction();
        try{
            $validatedData['store_status'] = 'accept';
            $validatedData['remarks'] = 'store status auto updated while registration';
            $this->updateStatus($storeCode,$validatedData);
            DB::commit();

        }catch(Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function togglePurchasePowerStatus($storeCode){
          try{
              $store = $this->storeRepository->findOrFailStoreByCode($storeCode);

            return $this->storeRepository->togglePurchasePower($store);
          }catch (Exception $exception){
            throw $exception;
          }
    }

    public function changeStoreStatus($storeCode,$status)
    {
        try{
            $store =  $this->storeRepository->findStoreByCode($storeCode);
            //dd($storeTypePackage);
            if($status == 'active'){
                $status = 1;
            }elseif($status == 'inactive'){
                $status = 0;
            }
            //dd($status);
            DB::beginTransaction();
            $store = $this->storeRepository->changeStoreStatus($store,$status);
            // dd($storeTypePackage);
            DB::commit();
            return $store;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateStoreMapLocation($validatedStore, $storeCode)
    {
        DB::beginTransaction();

        try {
            $store = $this->storeRepository->findOrFailStoreByCode($storeCode);

            $store = $this->storeRepository->updateStoreMapLocation($validatedStore,$store);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $store;
    }
}
