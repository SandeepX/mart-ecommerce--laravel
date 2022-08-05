<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/22/2020
 * Time: 1:41 PM
 */

namespace App\Modules\Store\Services\Payment;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\SMSProcessor\Jobs\SendSmsJob;
use App\Modules\Store\Helpers\StoreTransactionHelper;
use App\Modules\Store\Repositories\BalanceManagement\CashReceivedBalanceDetailRepository;
use App\Modules\Store\Repositories\BalanceManagement\SaleReconciliationRepository;
use App\Modules\Store\Repositories\BalanceManagement\StoreBalanceManagementRepository;
use App\Modules\Store\Repositories\BalanceManagement\StoreTransactionCorrectionDetailRepository;
use App\Modules\Store\Repositories\StoreRepository;
use Exception;
use Illuminate\Support\Facades\DB;


use App\Modules\Store\Models\Payments\StoreBalanceMaster;


class StoreBalanceControlService
{
    use ImageService;
    private $storeBalanceMgmtRepository;
    private $saleReconcilitaionRepository;
    private $storeTransactionCorrectionDetailRepository;
    private $cashReceivedBalanceDetailRepository;
    private $storeRepository;

    public function __construct(
        StoreBalanceManagementRepository $storeBalanceMgmtRepository,
        SaleReconciliationRepository $saleReconciliationRepository,
        StoreTransactionCorrectionDetailRepository $storeTransactionCorrectionDetailRepository,
        CashReceivedBalanceDetailRepository $cashReceivedBalanceDetailRepository,
        StoreRepository $storeRepository
    )
    {
        $this->storeBalanceMgmtRepository = $storeBalanceMgmtRepository;
        $this->saleReconciliationRepository = $saleReconciliationRepository;
        $this->storeTransactionCorrectionDetailRepository = $storeTransactionCorrectionDetailRepository;
        $this->cashReceivedBalanceDetailRepository = $cashReceivedBalanceDetailRepository;
        $this->storeRepository = $storeRepository;
    }

    public function saveBalanceControl($validated){

        try{
            DB::beginTransaction();
            $action_type = $validated['action_type'];

            $store = $this->storeRepository->findOrFailStoreByCode($validated['store_code']);

            if(!$store->isApproved()){
                throw new Exception('Store Is Not Approved');
            }


            if($action_type=='increment'){
                $this->saveStoreBalanceControlIncrement($validated);
            }else if($action_type == 'deduction'){
                $this->saveStoreBalanceControlDeduction($validated);
            }else{
                throw new Exception( 'Action Type Not Found');
            }

            DB::commit();

        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    private function saveStoreBalanceControlIncrement($validated){


        $current_balance = StoreTransactionHelper::getLatestStoreCumulativeBalance($validated['store_code']);
        $transaction_balance = $validated['transaction_amount'];

        if(isset($validated['proof_of_document'])){
            $fileNameToStore = $this->storeImageInServer($validated['proof_of_document'], StoreBalanceMaster::IMAGE_PATH);
            $validated['proof_of_document'] = $fileNameToStore;
        }

        $validated['created_by'] = getAuthUserCode();
        $validated['current_balance'] = $current_balance + roundPrice($transaction_balance);

        //dd($validated);

        $storeBalanceMaster = $this->storeBalanceMgmtRepository->saveTransaction($validated);

        $validated['store_balance_master_code'] = $storeBalanceMaster->store_balance_master_code;
        $validated['updated_by'] = getAuthUserCode();
        if($validated['transaction_type']=='sales_reconciliation_increment'){
            $validated['type'] = 'normal_store_order';
            $this->saleReconciliationRepository->saveTransaction($validated);
        }
        elseif($validated['transaction_type']=='pre_orders_sales_reconciliation_increment')
        {
            $validated['type'] = 'store_pre_order';
            $this->saleReconciliationRepository->saveTransaction($validated);
        }elseif($validated['transaction_type']=='transaction_correction_increment'){
            $this->storeTransactionCorrectionDetailRepository->saveTransaction($validated);
        }
        elseif($validated['transaction_type']=='cash_received'){
            $this->cashReceivedBalanceDetailRepository->saveTransaction($validated);
        }

        // send sms to store about balance credited

        $data['purpose'] = $validated['transaction_type'];
        $data['purpose_code'] = $storeBalanceMaster->store_balance_master_code;

        SendSmsJob::dispatch(
            $storeBalanceMaster->store->store_contact_mobile,
            'Your Current Account has been credited Rs. '.$transaction_balance. ' for '.$validated['transaction_type']. ' -@ https://allpasal.com/',
            $data
        );
    }

    private function saveStoreBalanceControlDeduction($validated){


        $current_balance = StoreTransactionHelper::getLatestStoreCumulativeBalance($validated['store_code']);
        $transaction_balance = $validated['transaction_amount'];

//        if($current_balance < $transaction_balance){
//            throw new Exception( 'Transaction Balance Cannot Be greater than Current Balance');
//        }

        if(isset($validated['proof_of_document'])){
            $fileNameToStore = $this->storeImageInServer($validated['proof_of_document'], StoreBalanceMaster::IMAGE_PATH);
            $validated['proof_of_document'] = $fileNameToStore;
        }

        $validated['current_balance'] = roundPrice($current_balance - roundPrice($transaction_balance));
        $validated['created_by'] = getAuthUserCode();

        $storeBalanceMaster = $this->storeBalanceMgmtRepository->saveTransaction($validated);
        $validated['store_balance_master_code'] = $storeBalanceMaster->store_balance_master_code;
        $validated['updated_by'] = getAuthUserCode();

        if($validated['transaction_type']=='sales_reconciliation_deduction'){
            $validated['type'] = 'normal_store_order';
            $this->saleReconciliationRepository->saveTransaction($validated);
        }
        elseif($validated['transaction_type']=='pre_orders_sales_reconciliation_deduction')
        {
            $validated['type'] = 'store_pre_order';
            $this->saleReconciliationRepository->saveTransaction($validated);

        }elseif($validated['transaction_type']=='transaction_correction_deduction'){
            $this->storeTransactionCorrectionDetailRepository->saveTransaction($validated);
        }

        // send sms to store about balance debited

        $data['purpose'] = $validated['transaction_type'];
        $data['purpose_code'] = $storeBalanceMaster->store_balance_master_code;
        SendSmsJob::dispatch(
            $storeBalanceMaster->store->store_contact_mobile,

            ('Your Current Account has been debited Rs. '.$transaction_balance. ' for '.$validated['transaction_type'].'  -@ https://allpasal.com/') ,

            $data
        );

    }









}
