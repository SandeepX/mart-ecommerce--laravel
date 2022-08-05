<?php
namespace  App\Modules\Wallet\Database\seeds;

use App\Modules\Store\Models\CashReceivedBalanceDetail;
use App\Modules\Store\Models\Payments\SaleReconciliation;
use App\Modules\Store\Models\Payments\StoreBalanceMaster;
use App\Modules\Store\Models\Payments\StoreLoadBalanceDetail;
use App\Modules\Store\Models\Payments\StoreTransactionCorrectionDetail;
use App\Modules\Store\Models\PreOrder\StoreBalancePreOrderDetail;
use App\Modules\Store\Models\StoreBalanceSalesDetail;
use App\Modules\Store\Models\StoreBalanceSalesReturnDetail;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\Wallet\Models\Wallet;
use App\Modules\Wallet\Models\WalletTransaction;
use App\Modules\Wallet\Models\WalletTransactionPurpose;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Svg\Style;

class WalletTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        try {

            DB::beginTransaction();

            $storeBalanceMasters = StoreBalanceMaster::all();
            $walletTXNAndSBMCode = [];

            $finalComparingArray = [];
            foreach ($storeBalanceMasters as $k => $storeBalanceMaster) {

                $data = [];
                $data['reference_code'] = rand(0000000001, 9999999999);
                $wallet = Wallet::where(
                    'wallet_holder_code',
                    $storeBalanceMaster->store_code
                )
                    ->first();

                $store = (new StoreRepository())->findOrFailStoreByCode($storeBalanceMaster->store_code);

                $data['wallet_code'] = $wallet->wallet_code;

                $walletTransactionPurpose = WalletTransactionPurpose::where(
                    'slug',
                    str_replace('_', '-', $storeBalanceMaster->transaction_type)
                )
                 ->where('user_type_code',$store->user->userType->user_type_code)
                 ->first();

                if(!$walletTransactionPurpose && $storeBalanceMaster->transaction_type === 'suspend_final_settlement'){
                 continue;
                }

                if(!$walletTransactionPurpose){
                    throw new \Exception('Wallet Transaction Code Not found'."\n".'');
                }

                $data['wallet_transaction_purpose_code']  =$walletTransactionPurpose->wallet_transaction_purpose_code;

                $data['amount'] = $storeBalanceMaster->transaction_amount;
                $data['transaction_uuid'] = Str::uuid();

                switch ($storeBalanceMaster->transaction_type) {
                    case 'load_balance':
                        $data['transaction_purpose_reference_code'] = StoreLoadBalanceDetail::where(
                            'store_balance_master_code',
                            $storeBalanceMaster->store_balance_master_code
                        )
                            ->first()
                            ->store_misc_payment_code;
                        break;
                    case 'sales':
                        $data['transaction_purpose_reference_code'] = StoreBalanceSalesDetail::where(
                            'store_balance_master_code',
                            $storeBalanceMaster->store_balance_master_code
                        )
                            ->first()
                            ->store_order_code;
                        break;
                    case 'sales_return':
                        $data['transaction_purpose_reference_code'] = StoreBalanceSalesReturnDetail::where(
                            'store_balance_master_code',
                            $storeBalanceMaster->store_balance_master_code
                        )
                            ->first()
                            ->store_order_code;
                        break;
                    case 'rewards':
                    case 'interest':
                    case 'refund_release':
                    case 'janata_bank_increment':
                    case 'withdraw':
                    case 'annual_charge':
                    case 'refundable':
                    case 'royalty':
                    case 'initial_registrations':
                    case 'non_refundable_registration_charge':
                        break;
                    case 'cash_received':
                        $cashRecieved = CashReceivedBalanceDetail::where('store_balance_master_code',$storeBalanceMaster->store_balance_master_code)
                                                                   ->first();
                        $data['meta'] =  json_encode(
                            [
                                'ref_bill_no' => $cashRecieved->ref_bill_no,
                            ]);
                        break;
                    case 'sales_reconciliation_increment':
                    case 'sales_reconciliation_deduction':
                    case 'pre_orders_sales_reconciliation_increment':
                    case 'pre_orders_sales_reconciliation_deduction':
                        $salesReconcilation = SaleReconciliation::where(
                            'store_balance_master_code', $storeBalanceMaster->store_balance_master_code
                        )
                            ->first();
                        $data['transaction_purpose_reference_code'] = $salesReconcilation->order_code;
                        $data['meta'] = json_encode(
                            [
                                'order_code' => $salesReconcilation->order_code,
                                'ref_bill_no' => $salesReconcilation->ref_bill_no,
                                'type' => $salesReconcilation->type
                            ]);
                        break;
                    case 'preorder_refund':
                    case 'preorder':
                        $storebalancePreOrderDetail = StoreBalancePreOrderDetail::where(
                            'store_balance_master_code',
                            $storeBalanceMaster->store_balance_master_code
                        )
                            ->first();
                        $data['transaction_purpose_reference_code'] = $storebalancePreOrderDetail->store_preorder_code;
                        break;
                    case 'transaction_correction_deduction':
                    case 'transaction_correction_increment':
                        $storeTransactionCorrectionDetail = StoreTransactionCorrectionDetail::where(
                            'store_balance_master_code',
                            $storeBalanceMaster->store_balance_master_code
                        )
                            ->first();
                       // dd($walletTXNAndSBMCode,$storeBalanceMaster->store_balance_master_code,$storeTransactionCorrectionDetail->transaction_code,$walletTXNAndSBMCode[$storeTransactionCorrectionDetail->transaction_code]);
                        $data['transaction_purpose_reference_code'] = $walletTXNAndSBMCode[$storeTransactionCorrectionDetail->transaction_code];
                        break;
                }



                $data['created_by'] = $storeBalanceMaster->created_by;
                $data['updated_by'] = $storeBalanceMaster->created_by;
                $data['remarks'] = $storeBalanceMaster->remarks;
                $data['proof_of_document'] = $storeBalanceMaster->proof_of_document;
                $data['created_at'] = $storeBalanceMaster->created_at;
                $data['updated_at'] = $storeBalanceMaster->updated_at;

                $walletUpdateData = [];
                $walletUpdateData['last_balance'] = $wallet->current_balance;
                if($walletTransactionPurpose->purpose_type == 'increment'){
                    $currentBalance =  $wallet->current_balance + $storeBalanceMaster->transaction_amount;
                    $walletUpdateData['current_balance'] =  roundPrice($currentBalance);
                }else{
                    $currentBalance =  $wallet->current_balance - $storeBalanceMaster->transaction_amount;
                    $walletUpdateData['current_balance'] =  roundPrice($currentBalance);
                }

                $wallet->update($walletUpdateData);
               $walletTxn =  WalletTransaction::create($data);

                $walletTXNAndSBMCode[$storeBalanceMaster->store_balance_master_code] = $walletTxn->wallet_transaction_code;

                if($storeBalanceMaster->transaction_type =='transaction_correction_deduction'
                    ||
                    $storeBalanceMaster->transaction_type =='transaction_correction_increment' ){

                   array_push($finalComparingArray,
                       [
                           $storeTransactionCorrectionDetail->transaction_code=> $walletTXNAndSBMCode[$storeTransactionCorrectionDetail->transaction_code],
                           $storeBalanceMaster->store_balance_master_code=>$walletTxn->wallet_transaction_code,
                       ]
                   );

                }


                echo "\033[32m".'Store '.$wallet->wallet_holder_code.
                    "\033[34m".' Current Balance:'.$walletUpdateData['current_balance'].
                    "\033[31m".' Last Balance: '.$walletUpdateData['last_balance'].
                    "\033[33m".' Type: '.$walletTransactionPurpose->purpose_type.
                    "\033[35m".' Amount: '.$storeBalanceMaster->transaction_amount.
                    "\033[32m".' Balance Master Code: '.$storeBalanceMaster->store_balance_master_code."\n";


            }

            echo " Successfully Completed "."\n";
           // dd($finalComparingArray);
            print_r($finalComparingArray);
            DB::commit();

        }catch (\Exception $exception){
            DB::rollback();
            echo $exception->getMessage();
        }
    }
}
