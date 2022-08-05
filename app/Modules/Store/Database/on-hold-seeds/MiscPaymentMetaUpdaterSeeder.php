<?php

namespace  App\Modules\Store\Database\seeds;

use App\Modules\Bank\Models\Bank;
use App\Modules\PaymentMedium\Models\DigitalWallet;
use App\Modules\PaymentMedium\Models\Remit;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPayment;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPaymentMeta;
use Illuminate\Database\Seeder;

class MiscPaymentMetaUpdaterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//
       $data = StoreMiscellaneousPayment::whereIn('payment_type',['cheque','remit','wallet'])->get();

        foreach($data as $datum)
        {

            $store_misc_payment_code=$datum->store_misc_payment_code;
           if($datum->payment_type=='cheque')
           {
               $deposit_bank=StoreMiscellaneousPaymentMeta::where('store_misc_payment_code',$store_misc_payment_code)->where('key','deposit_bank_name')->first();
               $bankDetail=Bank::where('bank_name',$deposit_bank->value)->first();
               StoreMiscellaneousPaymentMeta::where('store_misc_payment_code',$store_misc_payment_code)
                   ->updateOrCreate([
                       'store_misc_payment_code'=>$store_misc_payment_code,
                       'key'=>'bank_code',
                       'value'=>$bankDetail->bank_code
                   ]);

           }
           elseif($datum->payment_type=='remit')
           {
               $remit=StoreMiscellaneousPaymentMeta::where('store_misc_payment_code',$store_misc_payment_code)->where('key','remit_name')->first();
               $remitDetail=Remit::where('remit_name',$remit->value)->first();
               StoreMiscellaneousPaymentMeta::where('store_misc_payment_code',$store_misc_payment_code)
                   ->updateOrCreate([
                       'store_misc_payment_code'=>$store_misc_payment_code,
                       'key'=>'remit_code',
                       'value'=>$remitDetail->remit_code
                   ]);
           }
           elseif ($datum->payment_type=='wallet')
           {
               $wallet=StoreMiscellaneousPaymentMeta::where('store_misc_payment_code',$store_misc_payment_code)->where('key','payment_partner')->first();
               $walletDetail=DigitalWallet::where('wallet_name',$wallet->value)->first();
               StoreMiscellaneousPaymentMeta::where('store_misc_payment_code',$store_misc_payment_code)
                   ->updateOrCreate([
                       'store_misc_payment_code'=>$store_misc_payment_code,
                       'key'=>'wallet_code',
                       'value'=>$walletDetail->wallet_code
                   ]);
           }
        }

    }
}
