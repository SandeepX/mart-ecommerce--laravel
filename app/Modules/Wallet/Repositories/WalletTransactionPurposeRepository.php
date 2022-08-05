<?php


namespace App\Modules\Wallet\Repositories;

use App\Modules\Wallet\Models\WalletTransactionPurpose;
use Illuminate\Database\Eloquent\Model;
use Exception;

class WalletTransactionPurposeRepository
{

    public function getAllPaginatedWalletTransactionPurpose($paginatedBy = 10){
        return WalletTransactionPurpose::orderBy('wallet_transaction_purpose_code','desc')
                                        ->paginate($paginatedBy);
    }

    public function getAllWalletTransactionPurpose($select)
    {
        return WalletTransactionPurpose::select($select)
                                        ->orderBy('wallet_transaction_purpose_code','desc')
                                        ->get();
    }

    public function getAllWalletTransactionPurposeByFlow($transactionFlow,$select)
    {
        return WalletTransactionPurpose::select($select)->where('purpose_type',$transactionFlow)
            ->orderBy('wallet_transaction_purpose_code','desc')
            ->get();
    }



    public function getWalletTransactionPurposeByUserTypeCode($userTypeCode){
        return WalletTransactionPurpose::orderBy('wallet_transaction_purpose_code','desc')
            ->where('user_type_code',$userTypeCode)
            ->get();
    }


    public function findOrFailByTransactionPurposeCode($walletTransactionPurposeCode){
        $walletTransactionPurpose = WalletTransactionPurpose::where('wallet_transaction_purpose_code',$walletTransactionPurposeCode)
            ->first();
        if(!$walletTransactionPurpose){
           throw new Exception('Wallet Transaction Purpose Not found!');
        }
        return $walletTransactionPurpose;
    }

    public function findOrFailTransactionPurposeByFilterParams($walletTransactionPurposeCode,$filterParameters = []){
        $walletTransactionPurpose = WalletTransactionPurpose::where('wallet_transaction_purpose_code',$walletTransactionPurposeCode)
                                    ->when(isset($filterParameters['is_active']),function ($query) use ($filterParameters){
                                         $query->where('is_active',$filterParameters['is_active']);
                                    })
                                    ->when(isset($filterParameters['admin_control']),function ($query) use ($filterParameters){
                                        $query->where('admin_control',$filterParameters['admin_control']);
                                    })
                                    ->first();
        if(!$walletTransactionPurpose){
            throw new Exception('Wallet Transaction Purpose Not found!');
        }
        return $walletTransactionPurpose;
    }

    public function create($validated){
        return WalletTransactionPurpose::create($validated)->fresh();
    }

    public function update(WalletTransactionPurpose $walletTransactionPurpose,$validatedData){
        $walletTransactionPurpose->update($validatedData);
        return $walletTransactionPurpose->refresh();
    }

    public function delete(WalletTransactionPurpose $walletTransactionPurpose)
    {
        try{
            $walletTransactionPurpose->delete();
            $walletTransactionPurpose->deleted_by = getAuthUserCode();
            $walletTransactionPurpose->save();
            return $walletTransactionPurpose;
        }catch (Exception $exception){
            throw $exception;
        }
    }



    public function toggleStatus(WalletTransactionPurpose $walletTransactionPurpose){
        return $walletTransactionPurpose->update([
            'is_active' => ($walletTransactionPurpose->is_active) ? 0 : 1,
        ]);
    }

    public function getAllActiveControlTransactionPurposesByPurposeAndUserType($purposeType,$userTypeCode){
        $transactionPurposes = WalletTransactionPurpose::where('purpose_type',$purposeType)
                                    ->where('user_type_code',$userTypeCode)
                                    ->where('is_active',1)
                                    ->where('admin_control',1)
                                    ->get();

        return $transactionPurposes;

    }

    public function findOrFailWalletTransactionPurposeBySlugAndUserTypeCode($slug,$userTypeCode){

        try{

            $transactionPurposes = WalletTransactionPurpose::where('slug',$slug)
                ->where('user_type_code',$userTypeCode)
                ->first();

            if($transactionPurposes){
                return $transactionPurposes;
            }

            throw new Exception('Wallet transaction purpose not found!');

        }catch (Exception $exception){
            throw $exception;
        }

    }



}
