<?php

namespace  App\Modules\Wallet\Database\seeds;


use App\Modules\Wallet\Models\Wallet;
use App\Modules\Wallet\Models\WalletTransactionPurpose;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WalletTransactionPurposeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $creditTypes = Wallet::CREDIT_TYPES;
        $debitTypes = Wallet::DEBIT_TYPES;

        try{

            DB::beginTransaction();

            foreach($creditTypes as $creditType){
                $data = [];
                $data['purpose'] = ucwords(implode(' ',explode('_',$creditType)));
                $data['purpose_type'] = 'increment';
                $data['slug'] = implode('-',explode('_',$creditType));
                $data['is_active'] = 1;
                $data['admin_control'] = 1;
                $data['close_for_modification'] = 1;
                $data['user_type_code'] = 'UT004';
                if(in_array($creditType, ['load_balance','preorder_refund', 'sales_return'])){
                    $data['admin_control'] = 0;
                }
                if(in_array($creditType,['investment_commission','store_referred_commission'])){
                    $data['admin_control'] = 0;
                    $data['user_type_code'] = 'UT007';
                }
                $data['created_by'] = 'U00000001';
                $data['updated_by'] = 'U00000001';
                WalletTransactionPurpose::updateOrCreate([
                    'slug' => $data['slug'],
                    'user_type_code' => $data['user_type_code']
                ],
                    $data
                );
                echo 'wallet Transaction Purpose '.$creditType.' Inserted'."\n";
            }

            foreach($debitTypes as $debitType){
                $data = [];
                $data['purpose'] = ucwords(implode(' ',explode('_',$debitType)));
                $data['purpose_type'] = 'decrement';
                $data['slug'] = implode('-',explode('_',$debitType));
                $data['is_active'] = 1;
                $data['admin_control'] = 1;
                $data['close_for_modification'] = 1;
                if(in_array($debitType , ['sales',
                    'preorder',
                    'withdraw',
                    'non_refundable_registration_charge',
                    'initial_registrations'
                    ])){
                    $data['admin_control'] = 0;
                }
                $data['user_type_code'] = 'UT004';
                $data['created_by'] = 'U00000001';
                $data['updated_by'] = 'U00000001';
                WalletTransactionPurpose::updateOrCreate([
                    'slug' => $data['slug'],
                    'user_type_code' => $data['user_type_code']
                ],
                    $data
                );
                echo 'wallet Transaction Purpose '.$debitType.' Inserted'."\n";
            }

            echo "Sucessfully Completed all data "."\n";
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            echo $exception->getMessage();
        }



    }
}
