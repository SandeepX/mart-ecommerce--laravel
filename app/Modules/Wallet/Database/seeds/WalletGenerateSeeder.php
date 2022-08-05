<?php

namespace  App\Modules\Wallet\Database\seeds;

use App\Modules\Store\Models\Store;
use App\Modules\Wallet\Models\Wallet;
use App\Modules\Wallet\Services\WalletService;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WalletGenerateSeeder extends Seeder
{

    private $walletService;

    public function __construct(WalletService $walletService){
        $this->walletService = $walletService;
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        try{


            $stores = Store::whereNotIn('status',['pending','rejected'])->get();
            DB::beginTransaction();
            foreach ($stores as $store){
                $data = [];
                $data['wallet_uuid'] = Str::uuid();
                $data['wallet_holder_type'] = get_class($store);
                $data['wallet_type'] = strtolower(class_basename($store));
                $data['wallet_holder_code'] = $store->store_code;
                $data['current_balance'] = 0;
                $data['last_balance'] = 0;
                $data['is_active'] = 1;
                $data['created_by'] = 'U00000001';
                $data['updated_by'] = 'U00000001';

                $wallet = $this->walletService->findByHolderTypeAndHolderCode(
                    $data['wallet_holder_type'],
                    $data['wallet_holder_code']
                );

                if(!$wallet){
                    Wallet::create($data);
                    echo "\033[32m"."Wallet Created for Store: ".$store->store_name."(".$store->store_code.")","\n";
                }else{
                    echo "\033[31m"."Wallet Already Exists for Store: ".$store->store_name."(".$store->store_code.")","\n";
                }
            }

            echo "\033[32m".'sucessfull '."\n".'';
             DB::commit();
        }catch(Exception $exception){
            DB::rollback();
           echo $exception->getMessage();
        }
    }
}
