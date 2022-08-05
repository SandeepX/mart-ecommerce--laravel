<?php


namespace App\Modules\Wallet\Services;

use App\Modules\SalesManager\Classes\SalesManagerBalance;
use App\Modules\Store\Classes\StoreBalance;
use App\Modules\User\Models\User;
use App\Modules\Wallet\Factories\WalletCurrentBalanceDetailsFactory;
use App\Modules\Wallet\Repositories\WalletRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class WalletService
{

    private $walletRepository;

    public function __construct(
        WalletRepository $walletRepository

    ){
        $this->walletRepository = $walletRepository;
    }

    public function findOrFailByWalletCode($walletCode){
        return $this->walletRepository->findorFailByWalletCode($walletCode);
    }

    public function findorFailWalletByWalletCodeAndHolderType($walletCode,$walletHolderType){
        return $this->walletRepository->findorFailWalletByWalletCodeAndHolderType($walletCode,$walletHolderType);
    }

    public function findByHolderTypeAndHolderCode($walletCode,$walletHolderType){
        return $this->walletRepository->findByHolderTypeAndHolderCode($walletCode,$walletHolderType);
    }

    public function getAllPaginatedWallets($paginateBy = null){
       return $this->walletRepository->getAllPaginatedWallets($paginateBy);
    }


    public function createWallet($validatedWalletData){

         try{
             $wallet = $this->walletRepository->findByHolderTypeAndHolderCode(
                 $validatedWalletData['wallet_holder_type'],
                 $validatedWalletData['wallet_holder_code']
             );
             if(!$wallet){
                 return $this->walletRepository->create($validatedWalletData);
             }
            return false;
         }catch (Exception $exception){
             throw $exception;
         }
    }

    public function getWalletCurrentBalance(User $user){

        try{
         $walletCurrentBalanceDetail = (new WalletCurrentBalanceDetailsFactory($user))->getWalletCurrentBalance();
         return $walletCurrentBalanceDetail->getWalletCurrentBalanceDetails($user);
        }catch (Exception $exception){
            throw $exception;
        }

    }


}
