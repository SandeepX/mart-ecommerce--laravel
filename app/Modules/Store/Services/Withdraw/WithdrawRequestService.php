<?php
/**
 * Created by PhpStorm.
 * User: Sandeep
 * Date: 10/22/2020
 * Time: 1:41 PM
 */

namespace App\Modules\Store\Services\Withdraw;


use App\Modules\Store\Classes\StoreBalance;
use App\Modules\Store\Helpers\StoreTransactionHelper;
use App\Modules\Store\Models\Kyc\FirmKycBankDetail;
use App\Modules\Store\Models\Kyc\IndividualKYCBankDetail;
use App\Modules\Store\Repositories\BalanceManagement\StoreBalanceManagementRepository;

use App\Modules\Store\Repositories\Kyc\FirmKycRepository;
use App\Modules\Store\Repositories\Kyc\IndividualKycRepository;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\Store\Repositories\Withdraw\StoreBalanceFreezeRepository;
use App\Modules\Store\Repositories\Withdraw\WithdrawRequestRepository;
use Exception;
use Illuminate\Support\Facades\DB;


class WithdrawRequestService
{
    private $withdrawRequestRepository,$balanceFreezeRepository;
    private $individualKycRepository;
    private $firmKycRepository;

    private $storeBalance;
    private $storeRepository;


    public function __construct(
        WithdrawRequestRepository $withdrawRequestRepository,
        StoreBalanceFreezeRepository $balanceFreezeRepository,
        IndividualKycRepository $individualKycRepository,
        FirmKycRepository $firmKycRepository,
        StoreBalance $storeBalance,
        StoreRepository $storeRepository
    )
    {
        $this->withdrawRequestRepository = $withdrawRequestRepository;
        $this->balanceFreezeRepository = $balanceFreezeRepository;
        $this->individualKycRepository = $individualKycRepository;
        $this->firmKycRepository = $firmKycRepository;
        $this->storeBalance = $storeBalance;
        $this->storeRepository = $storeRepository;
    }

    /***withdraw balance request for api ******/
    public function saveBalanceWithdrawRequest($validatedData){

        try{
            DB::beginTransaction();

            $store = $this->storeRepository->findOrFailStoreByCode(getAuthStoreCode());
            $currentStoreBalance = $this->storeBalance->getStoreActiveBalance($store);

           // $currentStoreBalance = StoreTransactionHelper::getStoreCurrentBalance(getAuthStoreCode());
            if($currentStoreBalance < $validatedData['requested_amount']){
                throw new Exception('Cannot Withdraw More Than The Current Balance',403);
            }
            if($validatedData['kyc_type']=="firm")
            {
                $bankDetail= $this->firmKycRepository->getSingleBankDetailFromFirmKyc(
                    $validatedData['kyc_code'],
                    $validatedData['bank_code']
                );
            }elseif ($validatedData['kyc_type']=="sanchalak" || $validatedData['kyc_type']=="akhtiyari")
            {
                $bankDetail=$this->individualKycRepository->getSingleBankDetailFromIndividualKyc(
                    $validatedData['kyc_code'],
                    $validatedData['bank_code']
                );
            }
            $validatedData['account_no']=$bankDetail->bank_account_no;
            $validatedData['payment_body_code']=$bankDetail->bank_code;
            $validatedData['account_meta']=json_encode([
                'bank_branch_name'=>$bankDetail->bank_branch_name,
                'bank_account_holder_name'=>$bankDetail->bank_account_holder_name]);
            $withdrawRequestDetail = $this->withdrawRequestRepository->saveWithdrawRequestDetail($validatedData);

            DB::commit();
            $this->balanceFreezeRepository->saveFreezeBalance($withdrawRequestDetail);
            return $withdrawRequestDetail;

        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }


    public function getWithdrawRequestLists($storeCode,$paginatedBy)
    {
        return $this->withdrawRequestRepository->getWithdrawRequestLists($storeCode,$paginatedBy);
    }

    public function getWithdrawRequestListDetail($storeCode,$withdrawRequestCode)
    {
        return $this->withdrawRequestRepository->getWithdrawRequestListDetail($storeCode,$withdrawRequestCode);
    }
    public function getWithdrawRequestVerificationDetail($withdrawRequestCode,$paginatedBy)
    {
        return $this->withdrawRequestRepository->getWithdrawRequestVerificationDetail($withdrawRequestCode,$paginatedBy);
    }

    public function cancelBalanceWithdrawRequestBystore($withdrawRequestCode)
    {
        DB::beginTransaction();
        try{
            $withdrawDetail = $this->withdrawRequestRepository->getWithdrawRequestByCode($withdrawRequestCode);
            if(!$withdrawDetail){
                throw new Exception('Withdraw request detail not found',404);
            }
            if($withdrawDetail['status'] !='pending'){
                throw new Exception('Withdraw Request is already in '.$withdrawDetail['status'].' state');
            }
            $validatedData['remarks'] = 'withdraw request cancelled by store';
            $validatedData['status'] = 'cancelled';
            $withdrawRequestCancel = $this->withdrawRequestRepository->updateWithdrawRequestStatus($withdrawDetail,$validatedData);
            DB::commit();
            return $withdrawRequestCancel;
        }catch(Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
}




