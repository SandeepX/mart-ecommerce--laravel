<?php

namespace App\Modules\Store\Services\StorePackageTypes;

use App\Modules\Store\Repositories\StorePackageTypes\StoreTypePackageHistoryRepository;
use App\Modules\Store\Repositories\StorePackageTypes\StoreTypePackageRepository;
use Illuminate\Support\Facades\DB;

use Exception;

class StoreTypePackageMasterService
{
    private $storeTypePackageRepository,$storeTypePackageHistoryRepository;

    public function __construct(StoreTypePackageRepository $storeTypePackageRepository,
                   StoreTypePackageHistoryRepository $storeTypePackageHistoryRepository
    )
    {
        $this->storeTypePackageRepository = $storeTypePackageRepository;
        $this->storeTypePackageHistoryRepository = $storeTypePackageHistoryRepository;
    }


    public function getAllStoreTypePackages($storeTypeCode){
        return $this->storeTypePackageRepository->getAllStoreTypePackages($storeTypeCode);
    }


    public function findStoreTypePackageByCode($storeTPMCode){
        return $this->storeTypePackageRepository->findStoreTypePackageByCode($storeTPMCode);
    }

    public function findOrFailStoreTypePackageByCode($storeTPMCode)
    {
        return $this->storeTypePackageRepository->findOrFailStoreTypePackageByCode($storeTPMCode);
    }


    public function createStoreTypePackage($validatedData){
        DB::beginTransaction();
        try {

            $storeTypePackage = $this->storeTypePackageRepository
                                      ->createStoreTypePackage($validatedData);
            $storetypePackageHistory = $this->storeTypePackageHistoryRepository
                                            ->createStoreTypePackageHistory($storeTypePackage);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $storeTypePackage;
    }


    public function updateStoreTypePackage($validatedData, $storeTPMCode)
    {
        DB::beginTransaction();

        try {
            //update latest history row of that master_code
            $latestHistoryRow = $this->storeTypePackageHistoryRepository->findLatestHistoryRowByMasterCode($storeTPMCode);
            $latestHistoryRow = $this->storeTypePackageHistoryRepository->updateLatestHistoryRow($latestHistoryRow);

            //update store Type Package
            $storeTypePackage = $this->storeTypePackageRepository->findStoreTypePackageByCode($storeTPMCode);
            $storeTypePackage = $this->storeTypePackageRepository->updateStoreTypePackage($validatedData,$storeTypePackage);


            //store new data in history table
             $storeTypePackageHistory = $this->storeTypePackageHistoryRepository->createStoreTypePackageHistory($storeTypePackage);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $storeTypePackage;
    }

    public function deleteTypePackage($storeTPMCode)
    {
        DB::beginTransaction();
        try {
            $storeTypePackage = $this->storeTypePackageRepository->findStoreTypePackageByCode($storeTPMCode);
            if($storeTypePackage->is_active == 1)
            {
                throw new Exception('Active Store Type Package Can not be Deleted');
            }
            $storeTypePackage = $this->storeTypePackageRepository->delete($storeTypePackage);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $storeTypePackage;
    }

    public function changeStoreTypePackageStatus($storeTPMCode,$status)
    {
        try{
            $storeTypePackage =  $this->storeTypePackageRepository->findStoreTypePackageByCode($storeTPMCode);
            $latestPackageHistory = $this->storeTypePackageHistoryRepository->findLatestHistoryRowByMasterCode($storeTPMCode);
            //dd($storeTypePackage);
            if($status == 'active'){
                $status = 1;
            }elseif($status == 'inactive'){
                $status = 0;
            }
           //dd($status);
            DB::beginTransaction();
            $storeTypePackage = $this->storeTypePackageRepository->changeStoreTypePackageStatus($storeTypePackage,$status);
            $latestPackageHistory = $this->storeTypePackageHistoryRepository->changeStoreTypePackageHistoryStatus($latestPackageHistory,$status);
           // dd($storeTypePackage);
            DB::commit();
            return $storeTypePackage;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function changePackageDisplayOrder($storeTypeCode,$sortOrdersToChange)
    {
        try{

            DB::beginTransaction();
            $storeTypePackages = $this->storeTypePackageRepository->getAllStoreTypePackages($storeTypeCode);
            foreach ($storeTypePackages as $storeTypePackage) {
                $storeTypePackage->timestamps = false; // To disable update_at field updation
                $id = $storeTypePackage->id;

                foreach ($sortOrdersToChange as $order) {
                    if ($order['id'] == $id) {
                        $storeTypePackage->update(['sort_order' => $order['position']]);
                    }
                }
            }
            DB::commit();
            return $storeTypePackage;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

}
