<?php


namespace App\Modules\Store\Services\StorePackageTypes;


use App\Modules\Store\Repositories\StorePackageTypes\StorePackageHistoryRepository;
use App\Modules\Store\Repositories\StorePackageTypes\StoreTypePackageHistoryRepository;
use App\Modules\Store\Repositories\StoreRepository;
use Carbon\Carbon;

class StorePackageAdminService
{
    private $storeRepository;
    private $storePackageHistoryRepository;
    private $storeTypePackageHistoryRepository;

    public function __construct(
        StoreRepository $storeRepository,
        StorePackageHistoryRepository $storePackageHistoryRepository,
        StoreTypePackageHistoryRepository $storeTypePackageHistoryRepository
    ){
        $this->storeRepository = $storeRepository;
        $this->storePackageHistoryRepository = $storePackageHistoryRepository;
        $this->storeTypePackageHistoryRepository = $storeTypePackageHistoryRepository;

    }

    public function storeUpdatePackage($storeCode,$validatedData){
        try{
            $store  = $this->storeRepository->findOrFailStoreByCode($storeCode);
            $storeTypePackageHistory = $this->storeTypePackageHistoryRepository
                                            ->findorFailByStoreTypePackageHistoryCode(
                                                $validatedData['store_type_package_history_code']
                                            );

            // create history of old package of store
            $latestStoreHistoryPackage = $this->storePackageHistoryRepository->getLatestHistoryByStoreCode($storeCode);

            $validatedDataForHistory['store_code'] = $storeCode;
            $validatedDataForHistory['store_type_code'] =  $store->store_type_code;
            $validatedDataForHistory['store_type_package_history_code'] =  $store->store_type_package_history_code;
            $validatedDataForHistory['from_date'] =  $store->created_at;
            if($latestStoreHistoryPackage){
                $validatedDataForHistory['from_date'] = $latestStoreHistoryPackage->to_date;
            }
            $validatedDataForHistory['to_date'] = Carbon::now();
            $validatedDataForHistory['remarks'] = $validatedData['remarks'];
            $validatedDataForHistory['created_by'] = getAuthUserCode();
            $this->storePackageHistoryRepository->createStorePackageHistory($validatedDataForHistory);

             /// update package in store table
            $dataForStorePackage['store_type_code'] = $validatedData['store_type_code'];
            $dataForStorePackage['registration_charge'] = $storeTypePackageHistory->non_refundable_registration_charge;
            $dataForStorePackage['refundable_registration_charge'] = $storeTypePackageHistory->refundable_registration_charge;
            $dataForStorePackage['base_investment'] = $storeTypePackageHistory->base_investment;
            $dataForStorePackage['store_type_package_history_code'] = $storeTypePackageHistory->store_type_package_history_code;

            $store = $this->storeRepository->updateStorePackage($store,$dataForStorePackage);
            return $store;
        }catch (\Exception $exception){
            throw $exception;
        }
    }

}
