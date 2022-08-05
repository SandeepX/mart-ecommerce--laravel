<?php
/**
 * Created by PhpStorm.
 * User: Sandeep
 * Date: 10/22/2020
 * Time: 1:41 PM
 */

namespace App\Modules\Store\Services\BalanceManagement;


use App\Modules\Store\Helpers\StoreTransactionHelper;
use App\Modules\Store\Models\Kyc\FirmKycBankDetail;
use App\Modules\Store\Models\Kyc\IndividualKYCBankDetail;
use App\Modules\Store\Repositories\BalanceManagement\StoreBalanceManagementRepository;

use App\Modules\Store\Repositories\Kyc\FirmKycRepository;
use App\Modules\Store\Repositories\Kyc\IndividualKycRepository;
use App\Modules\Store\Repositories\Withdraw\StoreBalanceFreezeRepository;
use Exception;
use Illuminate\Support\Facades\DB;


class TransactionService
{
    private $storeBalancewithdrawRepo;
    private $individualKycRepository;
    private $firmKycRepository;
    private $balanceFreezeRepository;

    public function __construct(
        StoreBalanceManagementRepository $storeBalanceMgmtRepository,
        IndividualKycRepository $individualKycRepository,
        FirmKycRepository $firmKycRepository,
        StoreBalanceFreezeRepository $balanceFreezeRepository
    )
    {
        $this->storeBalancewithdrawRepo = $storeBalanceMgmtRepository;
        $this->individualKycRepository = $individualKycRepository;
        $this->firmKycRepository = $firmKycRepository;
        $this->balanceFreezeRepository = $balanceFreezeRepository;
    }

    public function getallTransactionOfstoreGroupBy()
    {
        return $this->storeBalancewithdrawRepo->getAllStoreTransactionFromBalanceMaster();
    }

//    public function getAllStoreBalanceTransaction($store_code)
//    {
//        return $this->storeBalancewithdrawRepo->getAllStoresTransactionFromBalanceMasterByStoreCode($store_code);
//    }

}




