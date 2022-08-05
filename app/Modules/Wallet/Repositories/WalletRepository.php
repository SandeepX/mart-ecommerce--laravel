<?php


namespace App\Modules\Wallet\Repositories;

use App\Modules\Wallet\Models\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Exception;

class WalletRepository
{


    public function __construct(){

    }

    public function findorFailByWalletCode($walletCode){
       $wallet = Wallet::where('wallet_code',$walletCode)->first();
        if(!$wallet){
            throw new Exception('Wallet Not found!');
        }
        return $wallet;
    }

    public function findorFailWalletByWalletCodeAndHolderType($walletCode,$walletHolderType){
          $wallet = Wallet::where('wallet_code',$walletCode)
              ->where('wallet_holder_type',$walletHolderType)
              ->first();
        if(!$wallet){
            throw new Exception('Wallet Not found!');
        }
        return $wallet;
    }

    public function getAllpaginatedWallets($paginateBy = 10){
        return Wallet::orderBy('wallet_code','desc')
            ->paginate($paginateBy);
    }


    public function findByHolderTypeAndHolderCode($walletHolderType,$walletHolderCode){
        return Wallet::where('wallet_holder_type',$walletHolderType)
                       ->where('wallet_holder_code',$walletHolderCode)
                        ->first();
    }

    public function create($validatedData){

        try{

            $authUserCode = Auth::check() ? getAuthUserCode() : getSuperAdminUserCode();
            $validatedData['wallet_uuid'] = Str::uuid();
            $validatedData['current_balance'] = 0;
            $validatedData['last_balance'] = 0;
            $validatedData['is_active'] = 1;
            $validatedData['created_by'] = $authUserCode;
            $validatedData['updated_by'] = $authUserCode;
            return  Wallet::create($validatedData);

        }catch (\Exception $exception){
            throw  $exception;
        }
    }


    public function updateWallet(Wallet $wallet,$validatedWalletData){
       $wallet->update($validatedWalletData);
       return $wallet->refresh();
    }

    public function getWalletLatestCurrentBalance($walletCode){
        $wallet = Wallet::where('wallet_code',$walletCode)->first();
        if($wallet){
          return roundPrice($wallet->current_balance);
        }
        return 0.00;
    }


}
