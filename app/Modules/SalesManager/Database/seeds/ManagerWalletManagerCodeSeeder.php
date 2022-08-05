<?php
namespace  App\Modules\SalesManager\Database\seeds;

use App\Modules\SalesManager\Models\Manager;
use App\Modules\Wallet\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Exception;

class ManagerWalletManagerCodeSeeder extends Seeder
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
            $wallets = Wallet::where('wallet_type','manager')->get();

            foreach ($wallets as $wallet){
                $manager = Manager::where('user_code',$wallet->wallet_holder_code)->first();
                $walletUpdateData = [];
                $walletUpdateData['wallet_holder_type'] = get_class($manager);
                $walletUpdateData['wallet_holder_code'] = $manager->manager_code;
                $wallet->update($walletUpdateData);
                echo "manager wallet is updated ".$wallet->wallet_code." \n";
            }
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            echo $exception->getMessage();
        }
    }
}
