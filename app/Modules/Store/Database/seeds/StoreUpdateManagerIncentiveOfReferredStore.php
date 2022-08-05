<?php
namespace App\Modules\Store\Database\seeds;

use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;
use App\Modules\Wallet\Models\Wallet;
use App\Modules\Wallet\Models\WalletTransaction;
use App\Modules\Wallet\Models\WalletTransactionPurpose;
use App\Modules\Wallet\Repositories\WalletRepository;
use App\Modules\Wallet\Repositories\WalletTransactionRepository;
use App\Modules\Wallet\Services\WalletTransactionService;
use Illuminate\Database\Seeder;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreUpdateManagerIncentiveOfReferredStore extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            DB::beginTransaction();

            $managers = User::where('user_type_code','UT007')
                            ->whereHas('salesManagerRegistrationStatus',function ($query){
                                $query->where('status','approved');
                            })
                            ->get();

            foreach ($managers as $manager){
                $stores = Store::where('referred_by',$manager->user_code)->where('status','approved')->get();
                if(count($stores) == 0){
                    continue;
                }
                foreach($stores as $store){
                    $incentiveAmount = $store->storeTypePackage->referal_registration_incentive_amount;
                    if(!$store->referred_incentive_amount){
                        if(count($store->orders->where('delivery_status','dispatched'))>0 || count($store->preOrders->where('status','dispatched'))>0){
                            $store->update(['referred_incentive_amount'=>$incentiveAmount]);
                        }else{

                            $wallet = $manager->wallet;
                            if($wallet){
                                $walletTransaction = $manager->wallet->walletTransactions()
                                    ->where('amount',$incentiveAmount)
                                    ->whereHas('walletTransactionPurpose',function ($query){
                                        $query->where('slug','store-referred-commission');
                                    })
                                    ->where('transaction_purpose_reference_code',$store->store_code)
                                    ->first();
                                if($walletTransaction){
                                    $walletTransactionData = [];
                                    $isNewReferenceCode = true;
                                    while ($isNewReferenceCode) {
                                        $referenceCode = $walletTransaction->generateReferenceCode();
                                        $walletTransactionData['reference_code'] = $referenceCode;
                                        $existingReference = (new WalletTransactionRepository())->findByReferenceCode($referenceCode);
                                        if (!$existingReference) {
                                            $isNewReferenceCode = false;
                                        }
                                    }
                                    $walletTransactionData['wallet_code'] = $manager->wallet->wallet_code;
                                    $walletTransactionData['wallet_transaction_purpose_code'] = WalletTransactionPurpose::where('slug','transaction-correction-deduction')
                                                                                                                        ->first()
                                                                                                                        ->wallet_transaction_purpose_code;
                                    $walletTransactionData['transaction_purpose_reference_code'] = $walletTransaction->wallet_transaction_code;
                                    $walletTransactionData['amount'] = $incentiveAmount;
                                    $walletTransactionData['transaction_uuid'] = Str::uuid();
                                    $walletTransactionData['remarks'] = 'Incentive amount deducted because Store ('.$store->store_name.'-'.$store->store_code.') has not made any dispatched orders till this moment :(';
                                    $walletTransactionData['created_by'] = 'U00000001';
                                    $walletTransactionData['updated_by'] = 'U00000001';

                                    $createdWalletTransaction =  WalletTransaction::create($walletTransactionData);


                                    $lastBalance= $wallet->current_balance;
                                    $currentBalance = $wallet->current_balance - $incentiveAmount;
                                    $wallet->update(
                                        [
                                            'last_balance' => $lastBalance,
                                            'current_balance'=>$currentBalance
                                        ]);
                                }

                            }else{

                                $walletData = [];
                                $walletData['wallet_holder_type'] = get_class($manager);
                                $walletData['wallet_type'] = 'manager';
                                $walletData['wallet_holder_code'] = $manager->user_code;
                                $walletData['wallet_uuid'] = Str::uuid();
                                $walletData['current_balance'] = 0;
                                $walletData['last_balance'] = 0;
                                $walletData['is_active'] = 1;
                                $walletData['created_by'] = 'U00000001';
                                $walletData['updated_by'] = 'U00000001';
                                Wallet::create($walletData);
                            }
                        }
                    }
                }
            }
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            echo $exception->getMessage();
        }
    }
}
