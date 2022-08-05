<?php


namespace App\Modules\Store\Services\StoreBalanceReconciliation;

use App\Modules\Store\Repositories\StoreBalanceReconciliation\BalanceReconciliationUsageRemarkRepository;
use App\Modules\Store\Repositories\StoreBalanceReconciliation\BalanceReconciliationUsageRepository;
use Exception;
use Illuminate\Support\Facades\DB;


class BalanceReconciliationUsagesRemakService
{
    private $balanceReconciliationUsagesRepo;
    private $balanceReconciliationUsageRemarkRepository;
    public function __construct(
        BalanceReconciliationUsageRepository $balanceReconciliationUsagesRepo,
        BalanceReconciliationUsageRemarkRepository $balanceReconciliationUsageRemarkRepository
    ){
        $this->balanceReconciliationUsagesRepo = $balanceReconciliationUsagesRepo;
        $this->balanceReconciliationUsageRemarkRepository = $balanceReconciliationUsageRemarkRepository;
    }

    public function createBalanceReconciliationUsagesRemark($balanceReconciliationUsagesCode,$validatedData){

        try{
            $balanceReconciliationUsages = $this->balanceReconciliationUsagesRepo
                                              ->findOrFailByBalanceReconciliationUsageCode(
                                                  $balanceReconciliationUsagesCode
                                              );

            $validatedData['balance_reconciliation_usages_code'] = $balanceReconciliationUsagesCode;
            $validatedData['created_by'] = getAuthUserCode();
            $validatedData['updated_by'] = getAuthUserCode();

            DB::beginTransaction();
                $remark =  $this->balanceReconciliationUsageRemarkRepository->storeBalanceReconciliationUsageRemarks(
                                $validatedData
                             );
                DB::commit();
            return $remark;

        }catch (Exception $exception){
            DB::rollBack();
           throw $exception;
        }

    }

}
