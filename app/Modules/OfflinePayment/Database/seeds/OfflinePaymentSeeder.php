<?php
namespace App\Modules\OfflinePayment\Database\seeds;

use App\Modules\InvestmentPlan\Models\InvestmentPlanSubscription;
use App\Modules\OfflinePayment\Models\OfflinePaymentDoc;
use App\Modules\OfflinePayment\Models\OfflinePaymentMaster;
use App\Modules\OfflinePayment\Models\OfflinePaymentMeta;
use App\Modules\OfflinePayment\Models\OfflinePaymentRemark;
use App\Modules\Store\Models\BalanceReconciliation\BalanceReconciliationUsage;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPayment;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPaymentDocument;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPaymentMeta;
use App\Modules\Wallet\Models\WalletTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Exception;

class OfflinePaymentSeeder extends Seeder
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
            $storeMiscellaneousPayments = StoreMiscellaneousPayment::with('paymentDocuments','miscellaneousPaymentRemarks','paymentMetaData','submittedBy','submittedBy.userType')
                                                                ->whereNull('online_payment_master_code')
                                                                ->orderBy('id','ASC')
                                                                ->get();

            foreach($storeMiscellaneousPayments as $storeMiscellaneousPayment){
                $offlinePaymentData = [];
                $offlinePaymentData['payment_for'] = $storeMiscellaneousPayment->payment_for;

                if($storeMiscellaneousPayment->payment_for == 'investment'){
                    $offlinePaymentData['reference_code'] = $storeMiscellaneousPayment->paymentMetaData()->where('key','investment_subscription_code')->first()->value;;
                }
                $offlinePaymentData['offline_payment_holder_namespace'] = $storeMiscellaneousPayment->submittedBy->userType->namespace;
                $namespaceArray =  explode("\\",$storeMiscellaneousPayment->submittedBy->userType->namespace);
                $offlinePaymentData['payment_holder_type'] = strtolower(end($namespaceArray));


                switch ($offlinePaymentData['payment_holder_type']){
                    case 'store':
                        $offlinePaymentData['offline_payment_holder_code'] = $storeMiscellaneousPayment->submittedBy->store->store_code;

                        break;
                    case 'manager':
                        $offlinePaymentData['offline_payment_holder_code'] = $storeMiscellaneousPayment->submittedBy->manager->manager_code;
                        break;
                    case 'user':
                        $offlinePaymentData['offline_payment_holder_code'] = $storeMiscellaneousPayment->submittedBy->user_code;
                        break;
                }

                $offlinePaymentData['payment_type'] = $storeMiscellaneousPayment->payment_type;
                $offlinePaymentData['deposited_by'] = $storeMiscellaneousPayment->deposited_by;
                $offlinePaymentData['transaction_date'] = $storeMiscellaneousPayment->transaction_date;
                $offlinePaymentData['contact_phone_no'] = $storeMiscellaneousPayment->contact_phone_no;
                $offlinePaymentData['amount'] = $storeMiscellaneousPayment->amount;
                $offlinePaymentData['verification_status'] = $storeMiscellaneousPayment->verification_status;
                $offlinePaymentData['responded_by'] = $storeMiscellaneousPayment->responded_by;
                $offlinePaymentData['responded_at'] = $storeMiscellaneousPayment->responded_at;
                $offlinePaymentData['remarks'] = $storeMiscellaneousPayment->remarks;
                $offlinePaymentData['has_matched'] = $storeMiscellaneousPayment->has_matched;
                $offlinePaymentData['questions_checked_meta'] = $storeMiscellaneousPayment->questions_checked_meta;
                $offlinePaymentData['created_by'] = $storeMiscellaneousPayment->user_code;
                $offlinePaymentData['created_at'] = $storeMiscellaneousPayment->created_at;
                $offlinePaymentData['updated_at'] = $storeMiscellaneousPayment->updated_at;



                //create offline payment master data
                $offlinePayment = OfflinePaymentMaster::create($offlinePaymentData);



                // offline payment meta data save
                if(count($storeMiscellaneousPayment->paymentMetaData) > 0){
                    foreach($storeMiscellaneousPayment->paymentMetaData as $storeMiscPaymentMeta){
                        $offlinePaymentMetaData = [];
                        $offlinePaymentMetaData['offline_payment_code'] = $offlinePayment->offline_payment_code;
                        $offlinePaymentMetaData['key'] = $storeMiscPaymentMeta->key;
                        $offlinePaymentMetaData['value'] = $storeMiscPaymentMeta->value;
                        $offlinePaymentMetaData['created_at'] = $storeMiscPaymentMeta->created_at;
                        $offlinePaymentMetaData['updated_at'] = $storeMiscPaymentMeta->updated_at;
                        OfflinePaymentMeta::create($offlinePaymentMetaData);
                    }
                }

                //offline payment docs data save
               if(count($storeMiscellaneousPayment->paymentDocuments)>0){
                   foreach($storeMiscellaneousPayment->paymentDocuments  as $storeMiscPaymentDoc){
                       $offlinePaymentDocData = [];
                       $offlinePaymentDocData['offline_payment_code'] = $offlinePayment->offline_payment_code;
                       $offlinePaymentDocData['document_type'] = $storeMiscPaymentDoc->document_type;
                       $offlinePaymentDocData['file_name'] = $storeMiscPaymentDoc->file_name;
                       $offlinePaymentDocData['created_at'] = $storeMiscPaymentDoc->created_at;
                       $offlinePaymentDocData['updated_at'] = $storeMiscPaymentDoc->updated_at;
                       OfflinePaymentDoc::create($offlinePaymentDocData);
                   }
               }

               //offline payment remarks data save
                if(count($storeMiscellaneousPayment->miscellaneousPaymentRemarks)>0){
                    foreach($storeMiscellaneousPayment->miscellaneousPaymentRemarks as $miscPaymentRemark){
                        $offlinePaymentRemarkData = [];
                        $offlinePaymentRemarkData['offline_payment_code'] = $offlinePayment->offline_payment_code;
                        $offlinePaymentRemarkData['remark'] = $miscPaymentRemark->remark;
                        $offlinePaymentRemarkData['created_by'] = $miscPaymentRemark->created_by;
                        $offlinePaymentRemarkData['created_at'] =  $miscPaymentRemark->created_at;
                        $offlinePaymentRemarkData['updated_at'] =  $miscPaymentRemark->updated_at;
                        OfflinePaymentRemark::create($offlinePaymentRemarkData);
                    }
                }

                //update reconciliation usages by new offline payment master
                $balanceReconciliationUsages = BalanceReconciliationUsage::where('used_for_code',$storeMiscellaneousPayment->store_misc_payment_code)
                                                                        ->first();
                if($balanceReconciliationUsages){
                    $balanceReconciliationUsages->update(['used_for_code'=>$offlinePayment->offline_payment_code]);
                }

                //update offline payment code in wallet transaction
                $walletTransactions = WalletTransaction::where('transaction_purpose_reference_code',$storeMiscellaneousPayment->store_misc_payment_code)
                                                        ->first();
                if($walletTransactions){
                     $walletTransactions->update(['transaction_purpose_reference_code'=>$offlinePayment->offline_payment_code]);
                }

                 //update offline payment payment cod ein subscription table
                if($storeMiscellaneousPayment->payment_for == 'investment'){
                    $investmentPlanSubscription = InvestmentPlanSubscription::where('ip_subscription_code',$offlinePayment->reference_code)
                                                ->first();
                    if($investmentPlanSubscription){
                          $investmentPlanSubscription->update(['payment_code' => $offlinePayment->offline_payment_code]);
                    }
                }

                echo "Successful seeding of ".$storeMiscellaneousPayment->store_misc_payment_code."\n";
            }
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            echo $exception->getMessage();
        }
    }
}
