<?php

namespace App\Modules\PaymentGateway\Database\seeds;

use App\Modules\PaymentGateway\Models\OnlinePaymentMeta;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPayment;
use App\Modules\Wallet\Models\WalletTransaction;
use Illuminate\Database\Seeder;
use Exception;
use Illuminate\Support\Facades\DB;

class OnlinePaymentMetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            $storeMiscPayments = StoreMiscellaneousPayment::with('paymentMetaData')
                                                           ->whereNotNull('online_payment_master_code')
                                                           ->orderBy('store_misc_payment_code','ASC')
                                                           ->get();
            DB::beginTransaction();
            foreach($storeMiscPayments as $storeMiscPayment){
               $paymentMetas = $storeMiscPayment->paymentMetaData;
               if(count($paymentMetas)>0){
                   foreach($paymentMetas as $paymentMeta){
                       $onlinePaymentMetaData = [];
                       $onlinePaymentMetaData['online_payment_code'] = $storeMiscPayment->online_payment_master_code;
                       $onlinePaymentMetaData['key'] = $paymentMeta->key;
                       $onlinePaymentMetaData['value'] = $paymentMeta->value;
                       $onlinePaymentMetaData['created_at'] = $paymentMeta->created_at;
                       $onlinePaymentMetaData['updated_at'] = $paymentMeta->updated_at;
                       OnlinePaymentMeta::create($onlinePaymentMetaData);
                   }
               }
               // change wallet transaction  -- purpose reference code
               $walletTransaction = WalletTransaction::where('transaction_purpose_reference_code',$storeMiscPayment->store_misc_payment_code)->first();
                 if($walletTransaction){
                     $walletTransaction->update(['transaction_purpose_reference_code'=>$storeMiscPayment->online_payment_master_code]);
                 }
            }
           DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            echo $exception->getMessage();
        }
    }
}
