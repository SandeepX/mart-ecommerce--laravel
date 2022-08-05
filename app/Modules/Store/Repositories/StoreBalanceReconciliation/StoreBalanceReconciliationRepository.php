<?php

namespace App\Modules\Store\Repositories\StoreBalanceReconciliation;
use App\Modules\Store\Models\BalanceReconciliation\StoreBalanceReconciliation;
use Illuminate\Support\Facades\DB;
use Exception;

class StoreBalanceReconciliationRepository
{

//    public function getAllBalanceReconciliation()
//    {
//        return StoreBalanceReconciliation::latest()->paginate(10);
//    }

    public function storeBalanceReconciliation($validated)
    {
       $validated['created_by'] =getAuthUserCode();
       $balanceReconciliation = StoreBalanceReconciliation::create($validated);
       return $balanceReconciliation;
    }

    public function show($balanceReconciliationCode)
    {
        return StoreBalanceReconciliation::where('balance_reconciliation_code',$balanceReconciliationCode)->firstorFail();
    }

    public function getBalanceReconciliationForVerification($balanceReconciliationData)
    {

        $balanceReconciliationDetail = StoreBalanceReconciliation::where('transaction_date',$balanceReconciliationData['transaction_date'])
                                            ->where('payment_body_code',$balanceReconciliationData['payment_body_code'])
                                            ->where('transaction_amount',$balanceReconciliationData['transaction_amount'])
                                            ->where('transaction_type',$balanceReconciliationData['transaction_type'])
                                            ->where('status','unused')
                                            ->where(function($query) use ($balanceReconciliationData)
                                            {
                                                if(!empty($balanceReconciliationData['transacted_by'])){
                                                    $query->where('description','LIKE','%'.$balanceReconciliationData['transacted_by'].'%');
                                                }
                                                if(isset($balanceReconciliationData['transaction_numbers']) && count($balanceReconciliationData['transaction_numbers'])>0){
                                                    foreach($balanceReconciliationData['transaction_numbers'] as $transaction_number){
                                                        $query->orWhere('description','LIKE','%'.$transaction_number.'%');
                                                    }
                                                }
                                                if(!empty($balanceReconciliationData['contact_phone_no'])){
                                                    $query->orWhere('description','LIKE','%'.$balanceReconciliationData['contact_phone_no'].'%');
                                                }
                                               if(!empty($balanceReconciliationData['cheque_no'])){
                                                   $query->orWhere('description','LIKE','%'.$balanceReconciliationData['cheque_no'].'%');
                                               }
                                                if(isset($balanceReconciliationData['remark'])){
                                                    $query->orWhere('description','LIKE','%'.$balanceReconciliationData['remark'].'%');
                                                }
                                            })->get();

        return $balanceReconciliationDetail;
    }



    public function getBalanceReconciliationForAdminVerfication($balanceReconciliationCode)
    {
        $balanceReconciliationDetail = StoreBalanceReconciliation::where('balance_reconciliation_code',$balanceReconciliationCode)
                                                                    ->where('status','unused')
                                                                    ->where('transaction_type','deposit')
                                                                    ->first();
        return $balanceReconciliationDetail;
    }

    public function updateOnlyStatusWhenMiscPaymentVerified($balanceReconcilation)
    {


        try{
            $balanceReconcilation->update([
                'status' => 'used',
            ]);
            return $balanceReconcilation;
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function updateOnlyStatusWhenOfflinePaymentVerified($balanceReconcilation)
    {
        try{
            $balanceReconcilation->update([
                'status' => 'used',
            ]);
            return $balanceReconcilation;
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function updateStoreReconciliation($validated,$balanceReconciliation)
    {
        $balanceReconciliation->update($validated);
        return $balanceReconciliation->fresh();
    }

    public function getPaymentBodyCode($table)
    {
        return DB::table($table)->get();
    }

    public function getUnusedReconcilation($offlinePayment,$adminDescription)
    {
        return StoreBalanceReconciliation::where('status','unused')
            ->where('transaction_date',$offlinePayment['transaction_date'])
            ->where('transaction_amount',$offlinePayment['amount'])
            ->where('description','like', '%' .$adminDescription. '%')
            ->get();
    }

}
