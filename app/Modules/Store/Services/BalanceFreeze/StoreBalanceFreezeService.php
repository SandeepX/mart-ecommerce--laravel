<?php
/**
 * Created by PhpStorm.
 * User: Sandeep
 * Date: 10/22/2020
 * Time: 1:41 PM
 */

namespace App\Modules\Store\Services\BalanceFreeze;


use App\Modules\Store\Helpers\StoreTransactionHelper;
use App\Modules\Store\Models\Balance\StoreBalanceWithdrawRequest;
use App\Modules\Store\Models\Kyc\FirmKycBankDetail;
use App\Modules\Store\Models\Kyc\IndividualKYCBankDetail;
use App\Modules\Store\Repositories\BalanceManagement\StoreBalanceManagementRepository;

use App\Modules\Store\Repositories\Kyc\FirmKycRepository;
use App\Modules\Store\Repositories\Kyc\IndividualKycRepository;
use App\Modules\Store\Repositories\Withdraw\StoreBalanceFreezeRepository;
use App\Modules\Store\Repositories\Withdraw\WithdrawRequestRepository;
use Exception;
use Illuminate\Support\Facades\DB;


class StoreBalanceFreezeService
{
    private $balanceFreezeRepository;

    public function __construct(
        StoreBalanceFreezeRepository $balanceFreezeRepository
    )
    {
        $this->balanceFreezeRepository = $balanceFreezeRepository;
    }


    public function changeFreezeStatusFromOneToZero($storeCode,$withdrawRequestCode)
    {
        return $this->balanceFreezeRepository->changeFreezeStatusFromOneToZero($storeCode,$withdrawRequestCode);
    }

}




