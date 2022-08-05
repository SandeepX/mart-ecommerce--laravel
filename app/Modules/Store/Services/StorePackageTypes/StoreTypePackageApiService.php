<?php

namespace App\Modules\Store\Services\StorePackageTypes;

use App\Modules\Store\Repositories\StorePackageTypes\StoreTypePackageHistoryRepository;
use App\Modules\Store\Repositories\StorePackageTypes\StoreTypePackageRepository;
use Illuminate\Support\Facades\DB;

use Exception;

class StoreTypePackageApiService
{
    private $storeTypePackageRepository,$storeTypePackageHistoryRepository;

    public function __construct(StoreTypePackageRepository $storeTypePackageRepository,
                                StoreTypePackageHistoryRepository $storeTypePackageHistoryRepository
    )
    {
        $this->storeTypePackageRepository = $storeTypePackageRepository;
        $this->storeTypePackageHistoryRepository = $storeTypePackageHistoryRepository;
    }


    public function getStoreTypePackageOfStoreType($storeTypeCode){
       return $this->storeTypePackageHistoryRepository->getStoreTypePackageOfStoreType($storeTypeCode);
    }

}
