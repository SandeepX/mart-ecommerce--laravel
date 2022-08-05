<?php

namespace App\Modules\Store\Services\StorePackageTypes;

use App\Modules\Store\Repositories\StorePackageTypes\StoreTypePackageHistoryRepository;
use App\Modules\Store\Repositories\StorePackageTypes\StoreTypePackageRepository;
use App\Modules\Store\Repositories\StorePackageTypes\StoreUpgradeRequestRepository;
use Illuminate\Support\Facades\DB;

use Exception;

class StorePackageUpgradeRequestApiService
{
    private $storeUpgradeRequestRepository;

    public function __construct(StoreUpgradeRequestRepository $storeUpgradeRequestRepository
    )
    {
        $this->storeUpgradeRequestRepository = $storeUpgradeRequestRepository;
    }


//    public function getStoreTypePackageOfStoreType($storeTypeCode){
//        return $this->storeTypePackageHistoryRepository->getStoreTypePackageOfStoreType($storeTypeCode);
//    }

    public function storeRequestedPackageUpgrade($validatedData)
    {
        try{
            DB::beginTransaction();
            $requestedUpgradePackage = $this->storeUpgradeRequestRepository->storeRequestedPackageUpgrade($validatedData);
            DB::commit();
            return $requestedUpgradePackage;
        }catch (Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
