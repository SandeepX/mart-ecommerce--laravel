<?php

namespace App\Modules\ManagerDiary\Services\VisitClaim;

use App\Modules\Application\Classes\UserDeviceDetector;
use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\ManagerDiary\Helpers\ManagerDiaryHelper;
use App\Modules\ManagerDiary\Helpers\ManagerPayPerVisitHelper;
use App\Modules\ManagerDiary\Models\VisitClaim\StoreVisitClaimRequestByManager;
use App\Modules\ManagerDiary\Repositories\Diary\ManagerDiaryRepository;
use App\Modules\ManagerDiary\Repositories\Diary\StoreVisitClaimRequestByManagerRepository;
use App\Modules\SalesManager\Events\ManagerWalletTransactionEvent;
use App\Modules\SalesManager\Services\SalesManagerService;
use App\Modules\Store\Repositories\StoreRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use function getAuthGuardUserCode;
use function getDistanceInKm;

class StoreVisitClaimRequestByManagerService
{
    use ImageService;
    private $storeVisitClaimRequestByManagerRepository;
    private $managerDiaryRepository;
    private $storeRepository;
    private $salesManagerService;

    public function __construct(
        StoreVisitClaimRequestByManagerRepository $storeVisitClaimRequestByManagerRepository,
        ManagerDiaryRepository $managerDiaryRepository,
        StoreRepository $storeRepository,
        SalesManagerService $salesManagerService
    ){
        $this->storeVisitClaimRequestByManagerRepository = $storeVisitClaimRequestByManagerRepository;
        $this->managerDiaryRepository = $managerDiaryRepository;
        $this->storeRepository = $storeRepository;
        $this->salesManagerService = $salesManagerService;
    }

    public function findorFailStoreVisitClaimRequestByCode($storeVisitClaimRequestCode,$with=[]){
      return $this->storeVisitClaimRequestByManagerRepository->findOrFailStoreVisitClaimRequestByCode($storeVisitClaimRequestCode,$with);
    }



    public function saveStoreVisitClaimRequestByManagerDetails($mangerDiaryCode,$validatedData){
        try{
            $with = ['referredStore'];
            $authUserCode = getAuthGuardUserCode();
            $authManagerCode = getAuthManagerCode();

            $managerDiary = $this->managerDiaryRepository->findOrFailManagerDiaryByCode($mangerDiaryCode,$with);
            if(!isset($managerDiary->latitude) || !isset($managerDiary->longitude)){
                throw new Exception('Latitude and Longitude required to generate QRCode');
            }

            $distanceBetweenManagerAndStore = getDistanceInKm($managerDiary->latitude,
                                                              $managerDiary->longitude,
                                                              $validatedData['manager_latitude'],
                                                              $validatedData['manager_longitude']
                                                          ) * 1000;

            $maximumDistance = StoreVisitClaimRequestByManager::MAXIMUM_DISTANCE;

            if($distanceBetweenManagerAndStore > $maximumDistance){
                throw new Exception('Distance to point of store recorded in manager diary should be within '.$maximumDistance.' meters');
            }
            $storeVisitClaimRequest = $this->storeVisitClaimRequestByManagerRepository->findLatestStoreVisitClaimRequestOfTodayByDiaryCode($mangerDiaryCode);
            if(isset($storeVisitClaimRequest) && $storeVisitClaimRequest->status != 'rejected'){
               throw new Exception('Visit claim request already exists of today');
            }
            if(isset($managerDiary->referredStore) && isset($managerDiary->referredStore->referredBy) && $managerDiary->referredStore->referredBy->manager_code  != $authManagerCode ){
                throw new Exception('Store should be referred by you');
            }

            if(!$managerDiary->referredStore){
                throw new Exception('Referred store not found in manager diary!');
            }

            if(!$managerDiary->referredStore->active() || !$managerDiary->referredStore->isApproved()){
               throw new Exception('Referred store should be active and approved');
            }
            $managerDeviceInfo = UserDeviceDetector::getMobileDeviceInfo();
//            if(!$managerDeviceInfo['is_mobile'] && !$managerDeviceInfo['is_tablet']){
//                throw new Exception('You can only generate visit claim request from mobile device');
//            }
            $validatedData['manager_diary_code'] = $mangerDiaryCode;
            $validatedData['created_by'] = $authUserCode;
            $validatedData['updated_by'] = $authUserCode;
            $validatedData['manager_device_info'] = json_encode($managerDeviceInfo);
            DB::beginTransaction();
            $storeVisitClaimRequest = $this->storeVisitClaimRequestByManagerRepository->store($validatedData);
            DB::commit();
            return $storeVisitClaimRequest;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function scanStoreVisitClaimRequestByStore($storeVisitClaimRequestCode,$storeCode,$validatedData){
        try{
            $with = ['managerDiary'];
            $store = $this->storeRepository->findOrFailStoreByCode($storeCode);
            $storeVisitClaimRequest = $this->storeVisitClaimRequestByManagerRepository
                                            ->findOrFailStoreVisitClaimRequestByCode($storeVisitClaimRequestCode,$with);

            if($storeVisitClaimRequest->qr_scanned_at){
              throw new Exception('Qr code already scanned :(');
            }

            if(!isset($storeVisitClaimRequest->managerDiary) && $storeVisitClaimRequest->managerDiary->referred_store_code != $storeCode){
                throw new Exception('Invalid store visit claim request :(');
            }

            if(!isset($storeVisitClaimRequest->manager_latitude) || !isset($storeVisitClaimRequest->manager_longitude)){
                throw new Exception('Manager QR generation Latitude and Longitude required to scan QR code');
            }

            $distanceBetweenManagerAndStoreUser = getDistanceInKm(
                                                    $storeVisitClaimRequest->manager_latitude,
                                                    $storeVisitClaimRequest->manager_longitude,
                                                    $validatedData['store_latitude'],
                                                    $validatedData['store_longitude']
                                                ) * 1000;

            $maximumDistance = StoreVisitClaimRequestByManager::MAXIMUM_DISTANCE;

            if($distanceBetweenManagerAndStoreUser > $maximumDistance){
                throw new Exception('Distance to point of qr generation should be within '.$maximumDistance.' meters');
            }
            if(!$store->active() || !$store->isApproved()){
                throw new Exception('Store should be active and approved');
            }
            $storeDeviceInfo = UserDeviceDetector::getMobileDeviceInfo();
//            if(!$storeDeviceInfo['is_mobile'] && !$storeDeviceInfo['is_tablet']){
//                throw new Exception('You can only generate visit claim request from mobile device');
//            }
            $validatedData['store_device_info'] = json_encode($storeDeviceInfo);
            $validatedData['qr_scanned_at'] = Carbon::now();
            $validatedData['updated_by'] = getAuthGuardUserCode();
            DB::beginTransaction();
            $storeVisitClaimRequest = $this->storeVisitClaimRequestByManagerRepository
                                           ->update($storeVisitClaimRequest,$validatedData);
            DB::commit();
            return $storeVisitClaimRequest;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

    public function submitScannedStoreVisitClaimRequestDetailsByManager($storeVisitClaimRequestCode,$managerCode, $validatedData){
        try{
            $with = ['managerDiary'];
            $storeVisitClaimRequest = $this->storeVisitClaimRequestByManagerRepository
                ->findOrFailStoreVisitClaimRequestByCode($storeVisitClaimRequestCode,$with);
            if(!$storeVisitClaimRequest->qr_scanned_at){
                throw new Exception('This visit claim should be scanned first by store');
            }

            if($storeVisitClaimRequest->submitted_at){
               throw new Exception('This visit claim is already submitted');
            }

            if(isset($storeVisitClaimRequest->managerDiary) && $storeVisitClaimRequest->managerDiary->manager_code != $managerCode){
               throw new Exception('Invalid store visit claim :(');
            }

            if(isset($validatedData['visit_image'])){
                $fileNameToStore = $this->storeImageInServer($validatedData['visit_image'], StoreVisitClaimRequestByManager::VISIT_IMAGE_PATH);
                $validatedData['visit_image'] = $fileNameToStore;
            }
            $validatedData['pay_per_visit'] = ManagerPayPerVisitHelper::getManagerPayPerVisit($managerCode);
            $validatedData['status'] = 'pending';
            $validatedData['submitted_at'] = Carbon::now();
            $validatedData['updated_by'] = getAuthGuardUserCode();
            DB::beginTransaction();
            $storeVisitClaimRequest = $this->storeVisitClaimRequestByManagerRepository
                                            ->update($storeVisitClaimRequest,$validatedData);
            DB::commit();
            return $storeVisitClaimRequest;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function respondToVisitClaimRequest($storeVisitClaimRequestCode,$validatedData){
        try{
            $with = ['managerDiary','managerDiary.manager'];
            $storeVisitClaimRequest = $this->storeVisitClaimRequestByManagerRepository
                ->findOrFailStoreVisitClaimRequestByCode($storeVisitClaimRequestCode,$with);

            if($storeVisitClaimRequest->responded_at){
                throw new Exception('This visit claim is already responded');
            }
            if($storeVisitClaimRequest->status != 'pending'){
                throw new Exception('This store visit claim request should be in pending state :(');
            }
            $validatedData['responded_by'] = getAuthUserCode();
            $validatedData['responded_at'] = Carbon::now();
            $validatedData['updated_by'] =  getAuthUserCode();

            DB::beginTransaction();
            $storeVisitClaimRequest = $this->storeVisitClaimRequestByManagerRepository
                                                    ->update($storeVisitClaimRequest,$validatedData);

            if($storeVisitClaimRequest->status == 'verified' && $storeVisitClaimRequest->pay_per_visit > 0){
                $this->prepareWalletTransactionForSalesManagerVisitClaimReward($storeVisitClaimRequest);
            }

            DB::commit();
            return $storeVisitClaimRequest;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }


    public function prepareWalletTransactionForSalesManagerVisitClaimReward(StoreVisitClaimRequestByManager $storeVisitClaimRequest,$smsStatus=true){
        $manager = $storeVisitClaimRequest->managerDiary->manager;
        $walletTransaction['wallet'] = $manager->wallet;
        $walletTransaction['wallet_transaction_purpose'] = $this->salesManagerService->getSalesManagerVisitClaimRewardWalletTransactionPurpose();
        $walletTransaction['amount'] = roundPrice($storeVisitClaimRequest->pay_per_visit);
        $walletTransaction['remarks'] = 'Balance for Visit Claim Reward of '.$storeVisitClaimRequest->store_visit_claim_request_code;
        $walletTransaction['transaction_purpose_reference_code'] = $storeVisitClaimRequest->store_visit_claim_request_code;
        $walletTransaction['transaction_notification_details'] = [
            'sms' => [
                'contact_no' => $manager->manager_phone_no,
                'status' => $smsStatus,
                'message' => "You current account has been credited with Rs. {$walletTransaction['amount']} due to Visit Claim Reward {$storeVisitClaimRequest->store_visit_claim_request_code}   @ https://allpasal.com/ "
            ]
        ];
        if($storeVisitClaimRequest->pay_per_visit > 0){
            event(new ManagerWalletTransactionEvent($walletTransaction));
        }
    }

}
