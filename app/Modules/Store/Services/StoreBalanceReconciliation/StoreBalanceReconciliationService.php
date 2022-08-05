<?php


namespace App\Modules\Store\Services\StoreBalanceReconciliation;

use App\Modules\OfflinePayment\Repositories\OfflinePaymentMetaRepository;
use App\Modules\OfflinePayment\Repositories\OfflinePaymentRepository;
use App\Modules\Store\Models\BalanceReconciliation\BalanceReconciliationUsage;
use App\Modules\Store\Repositories\Payment\StoreMiscellaneousPaymentRepository;
use App\Modules\Store\Repositories\StoreBalanceReconciliation\BalanceReconciliationUsageRemarkRepository;
use App\Modules\Store\Repositories\StoreBalanceReconciliation\BalanceReconciliationUsageRepository;
use App\Modules\Store\Repositories\StoreBalanceReconciliation\StoreBalanceReconciliationRepository;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;


class StoreBalanceReconciliationService
{
    private $storeBalanceReconciliationRepo;
    private $balanceReconciliationUsageRemarkRepository;
    private $offlinePaymentMetaRepository;
    private $offlinePaymentRepository;

    public function __construct(
        StoreBalanceReconciliationRepository $storeBalanceReconciliationRepo,
        BalanceReconciliationUsageRepository $balanceReconciliationUsageRepository,
        BalanceReconciliationUsageRemarkRepository $balanceReconciliationUsageRemarkRepository,
        OfflinePaymentMetaRepository $offlinePaymentMetaRepository,
        OfflinePaymentRepository $offlinePaymentRepository
    )
    {
        $this->storeBalanceReconciliationRepo = $storeBalanceReconciliationRepo;
        $this->balanceReconciliationUsageRepository = $balanceReconciliationUsageRepository;
        $this->balanceReconciliationUsageRemarkRepository =  $balanceReconciliationUsageRemarkRepository;
        $this->offlinePaymentMetaRepository = $offlinePaymentMetaRepository;
        $this->offlinePaymentRepository = $offlinePaymentRepository;
    }

//    public function getAllBalanceReconciliation()
//    {
//        return $this->storeBalanceReconciliationRepo->getAllBalanceReconciliation();
//    }

    public function createBalanceReconciliation($validated)
    {
        DB::beginTransaction();
        try {

            $offlinePayment = $this->offlinePaymentRepository->getBalanceOfflinePaymentForMatching($validated);

            if(!empty($offlinePayment)){
                $this->offlinePaymentRepository->updateOfflinePayment($offlinePayment,['has_matched'=>1]);
            }
            $balanceReconciliation = $this->storeBalanceReconciliationRepo->storeBalanceReconciliation($validated);
            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
       return $balanceReconciliation;
    }


    public function findorfailBalanceReconciliationByCode($balanceReconciliationCode)
    {
        return $this->storeBalanceReconciliationRepo->show($balanceReconciliationCode);
    }

    public function updateStoreReconciliationDetailByCode($validated,$balanceReconciliationCode)
    {

        $balanceReconciliation = $this->findorfailBalanceReconciliationByCode($balanceReconciliationCode);

        try {
            DB::beginTransaction();
            $validated['updated_by'] = getAuthUserCode();
            if ($balanceReconciliation->status == 'used') {
                throw new Exception('Cannot update the details for "used" balance reconciliation record !');
            }
            $this->storeBalanceReconciliationRepo->updateStoreReconciliation($validated,$balanceReconciliation);

            DB::commit();
        }catch(Exception $exception){
            throw $exception;
        }

    }

    public function getBalanceReconciliationForVerificationForLoadbalance($offlinePayment)
    {

        try{
            $allPaymentBody = ['bank_code','remit_code','wallet_code'];
            $paymentBodyMapper = [
                'cash'=> 'bank_code',
                'cheque' => 'bank_code',
                'mobile_banking'=>'bank_code',
                'wallet'=>'wallet_code',
                'remit'=>'remit_code'
            ];
            if(!array_key_exists($offlinePayment->payment_type,$paymentBodyMapper)){
                throw new Exception('No Any Payment Type Found :(');
            }

            $paymentBody = $offlinePayment['paymentMetaData']->where('key',$paymentBodyMapper[$offlinePayment->payment_type])->first();
            if(!($paymentBody)){
                throw new Exception(
                    'cannot find payment body  of this misc payment.'
                );
            }
            $paymentBodyCode = $paymentBody->value;

          //  $payment_meta = $offlinePayment['paymentMetaData'];
            //dd($paymentBodyCode);
//            foreach($payment_meta as $key =>$metaDetail){
//                $payment_method = $metaDetail->key;
//                if(in_array($payment_method,$allPaymentBody,true)){
//                    $paymentBodyCode = $metaDetail->value;
//                }
//            }


            $balanceReconcilationData['payment_body_code'] = $paymentBodyCode;
            $balanceReconcilationData['transaction_amount'] = $offlinePayment->amount;
            $balanceReconcilationData['transaction_date'] = $offlinePayment->transaction_date;
            $balanceReconcilationData['transaction_type'] = 'deposit';

            $balanceReconcilationData['transacted_by'] = $offlinePayment->deposited_by;
            $balanceReconcilationData['transaction_no'] = $offlinePayment->voucher_number;
            $balanceReconcilationData['contact_phone_no'] = $offlinePayment->contact_phone_no;
            $balanceReconcilationData['payment_type'] = $offlinePayment->payment_type;

            if($balanceReconcilationData['payment_type'] == 'cheque'){
                $payment_meta = $offlinePayment['paymentMetaData'];
                foreach($payment_meta as $key =>$metaDetail){
                    $payment_type = $metaDetail->key;
                    if($payment_type == 'cheque_account_number'){
                        $cheque_no = $metaDetail->value;
                    }
                }
            }else{
                $cheque_no ='';
            }
            $balanceReconcilationData['cheque_no'] = $cheque_no;

            $balanceReconcilationDataByNormalSearch = $this->storeBalanceReconciliationRepo->getBalanceReconciliationForVerification($balanceReconcilationData);
           if($balanceReconcilationDataByNormalSearch->isEmpty()){
               $offlinePaymentsMetaData = $this->offlinePaymentMetaRepository->getPaymentAdminDescriptionMetaDetail($offlinePayment['offline_payment_code'],$select=['value']);
               if($offlinePaymentsMetaData){
                   $adminDescription = removeSpecialChar($offlinePaymentsMetaData['value']);
                   $balanceReconcilationByAdminDescription = $this->storeBalanceReconciliationRepo->getUnusedReconcilation($offlinePayment,$adminDescription);
                   if($balanceReconcilationByAdminDescription)
                   {
                       return $balanceReconcilationByAdminDescription;
                   }
               }
           }else{
               return $balanceReconcilationDataByNormalSearch;
           }
       }catch(Exception $exception){
           throw $exception;
       }
    }




    public function getPaymentBodyCode($payment_method)
    {
        $html ='';
        if($payment_method=='bank'){
            $table  = 'banks';
            $paymentBodyDetail = $this->storeBalanceReconciliationRepo->getPaymentBodyCode($table);
            $html.='<option value="">--Select Bank--</option>';
            foreach ($paymentBodyDetail as $key => $bankdetail) {
                    $html.='<option value="'.$bankdetail->bank_code.'">'.$bankdetail->bank_name.'</option>';
            }

        }elseif($payment_method =='remit'){
            $table ='remits';
            $paymentBodyDetail = $this->storeBalanceReconciliationRepo->getPaymentBodyCode($table);
            $html.='<option value="">--Select Remit--</option>';
            foreach ($paymentBodyDetail as $key => $detail) {
                $html.='<option value="'.$detail->remit_code .'">'.$detail->remit_name .'</option>';
            }

        }elseif($payment_method=='digital_wallet'){
            $table = 'digital_wallets';
            $paymentBodyDetail = $this->storeBalanceReconciliationRepo->getPaymentBodyCode($table);
            $html.='<option value="">--Select  Digital wallet--</option>';
            foreach ($paymentBodyDetail as $key => $detail) {
                $html.='<option value="'.$detail->wallet_code .'">'.$detail->wallet_name .'</option>';
            }
        }else{
            $html.='';
        }
        return $html;

    }


    public function getPaymentBodyForUpdate($request)
    {
        $payment_method = $request->payment_method;
        $payment_body_code = $request->body_code;

        $html ='';
        if($payment_method=='bank'){
            $table  = 'banks';
            $paymentBodyDetail = $this->storeBalanceReconciliationRepo->getPaymentBodyCode($table);

            foreach ($paymentBodyDetail as $key => $detail) {

                $html.="<option value=$detail->bank_code"." ";
                if ($detail->bank_code == $payment_body_code){
                    $html.='selected';
                }
                $html.=">$detail->bank_name</option>";
            }

        }elseif($payment_method =='remit'){
            $table ='remits';
            $paymentBodyDetail = $this->storeBalanceReconciliationRepo->getPaymentBodyCode($table);

            foreach ($paymentBodyDetail as $key => $detail) {
                $html.="<option value=$detail->remit_code"." ";
                if ($detail->remit_code == $payment_body_code){
                    $html.='selected';
                }
                $html.=">$detail->remit_name</option>";
            }

        }elseif($payment_method=='digital_wallet'){
            $table = 'digital_wallets';
            $paymentBodyDetail = $this->storeBalanceReconciliationRepo->getPaymentBodyCode($table);
            foreach ($paymentBodyDetail as $key => $detail) {
                $html.="<option value=$detail->wallet_code"." ";
                if ($detail->wallet_code == $payment_body_code){
                    $html.='selected';
                }
                $html.=">$detail->wallet_name</option>";
            }
        }else{
            $html.='';
        }
        //dd($html);

        return $html;
    }











//    public function deleteBalanceReconciliationByCode($balanceReconciliationCode)
//    {
//        try {
//             return $this->storeBalanceReconciliationRepo->destroy($balanceReconciliationCode);
//
//        } catch (Exception $exception) {
//            throw  $exception;
//        }
//
//    }

     public function changeStatusFromUnusedToUsed($balanceReconciliationCode,$remarks=null){
           $balanceReconciliation = $this->findorfailBalanceReconciliationByCode($balanceReconciliationCode);

           try {
               DB::beginTransaction();
               if ($balanceReconciliation->status == 'used') {
                   throw new Exception('Cannot change the status for "used" balance reconciliation record !');
               }


               $validated['status'] = 'used';
               $this->storeBalanceReconciliationRepo->updateStoreReconciliation($validated,$balanceReconciliation);

              $balanceReconciliationUsage =  $this->balanceReconciliationUsageRepository->storeBalanceReconiliationUsage([
                   'balance_reconciliation_code' => $balanceReconciliation->balance_reconciliation_code,
                   'used_for' => 'locked',
                   'used_for_code' => 'locked',
                   'created_by' => getAuthUserCode()
               ]);

              $this->balanceReconciliationUsageRemarkRepository->storeBalanceReconciliationUsageRemarks([
                  'balance_reconciliation_usages_code' => $balanceReconciliationUsage->balance_reconciliation_usages_code,
                  'remark' => $remarks,
                  'created_by' => getAuthUserCode(),
                  'updated_by' => getAuthUserCode()
              ]);

               DB::commit();
           }catch(Exception $exception){
               throw $exception;
           }

     }
    public function getBalanceReconciliationUsage($usedForCode){
        return $this->balanceReconciliationUsageRepository->getBalanceReconciliationUsage($usedForCode);
    }



}
