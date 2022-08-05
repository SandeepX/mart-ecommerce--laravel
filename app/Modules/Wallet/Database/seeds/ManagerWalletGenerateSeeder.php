<?php

namespace  App\Modules\Wallet\Database\seeds;

use App\Modules\User\Models\User;
use App\Modules\Wallet\Models\Wallet;
use App\Modules\Wallet\Services\WalletService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ManagerWalletGenerateSeeder extends Seeder
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

                $approvedSalesManagers = User::whereHas('userType', function ($query) {
                        $query->where('slug','sales-manager');
                    })
                    ->whereHas('salesManagerRegistrationStatus',function ($query){
                        $query->where('status','approved');
                    })
                    ->get();


                DB::beginTransaction();

            foreach($approvedSalesManagers as $approvedSalesManager){

                $data = [];
                $data['wallet_uuid'] = Str::uuid();
                $data['wallet_holder_type'] = get_class($approvedSalesManager);
                $data['wallet_type'] = 'manager';
                $data['wallet_holder_code'] = $approvedSalesManager->user_code;
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
                    echo "\033[32m"."Wallet Created for Manager:".$approvedSalesManager->name."(".$approvedSalesManager->user_code.")","\n";
                }else{
                    echo "\033[31m"."Wallet Already Exists for Manager:".$approvedSalesManager->name."(".$approvedSalesManager->user_code.")","\n";
                }
            }

            DB::commit();
            echo "\033[32m"."sucessfull"."\n";

        }catch (\Exception $exception){
          DB::rollback();
          echo $exception->getMessage();
        }



    }
}
