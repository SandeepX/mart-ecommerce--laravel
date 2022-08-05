<?php


namespace App\Modules\Wallet\Services;

use App\Modules\Wallet\Helpers\WalletTransactionPurposeHelper;
use App\Modules\Wallet\Models\WalletTransactionPurpose;
use App\Modules\Wallet\Repositories\WalletTransactionPurposeRepository;
use App\Modules\Wallet\Repositories\WalletTransactionRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class WalletTransactionPurposeService
{

    private $walletTransactionPurposeRepository;
    private $walletTransactionRepository;

    public function __construct(
        WalletTransactionPurposeRepository $walletTransactionPurposeRepository,
        WalletTransactionRepository $walletTransactionRepository
    )
    {
        $this->walletTransactionPurposeRepository = $walletTransactionPurposeRepository;
        $this->walletTransactionRepository = $walletTransactionRepository;
    }

    public function getAllPaginatedWalletTransactionPurpose($paginatedBy = null){
        return $this->walletTransactionPurposeRepository->getAllPaginatedWalletTransactionPurpose($paginatedBy);
    }
    public function getAllWalletTransactionPurpose($select){
        return $this->walletTransactionPurposeRepository->getAllWalletTransactionPurpose($select);
    }

    public function getAllWalletTransactionPurposeByFlow($transactionFlow,$select){
        return $this->walletTransactionPurposeRepository->getAllWalletTransactionPurposeByFlow($transactionFlow,$select);
    }

    public function getWalletTransactionPurposeByUserTypeCode($userTypeCode){
        return $this->walletTransactionPurposeRepository->getWalletTransactionPurposeByUserTypeCode($userTypeCode);
    }

    public function findorFailByTransactionPurposeCode($walletTransactionPurposeCode){
            return  $this->walletTransactionPurposeRepository->findOrFailByTransactionPurposeCode($walletTransactionPurposeCode);
    }
    public function findOrFailTransactionPurposeByFilterParams($walletTransactionPurposeCode,$filterParameters=[]){
        return  $this->walletTransactionPurposeRepository->findOrFailTransactionPurposeByFilterParams($walletTransactionPurposeCode,$filterParameters);

    }

    public function storeWalletTransactionPurpose($validated){

        try {
            DB::beginTransaction();
            foreach ($validated['user_type'] as $key => $userTypeCode){
                $validatedData['purpose'] = $validated['purpose'];
                $validatedData['purpose_type'] = $validated['purpose_type'];
                $validatedData['slug'] = make_slug($validated['purpose']);
                $validatedData['user_type_code'] = $userTypeCode;

                if(WalletTransactionPurposeHelper::checkIfAlreadyExistsTransactionPurpose(
                                $validatedData['user_type_code'],
                                $validatedData['slug'])
                ){
                    throw new Exception('Wallet Transaction Purpose Already Exists for this
                    Purpose: '.$validatedData["purpose"].' and User Type Code: '.$validatedData['user_type_code']);
                }

                $validatedData['is_active'] = $validated['is_active'][$key];
                $validatedData['admin_control'] = $validated['admin_control'][$key];
                $validatedData['close_for_modification'] = $validated['close_for_modification'][$key];
                $validatedData['created_by'] = getAuthUserCode();
                $validatedData['updated_by'] = getAuthUserCode();

                $walletTransactionPurpose = $this->walletTransactionPurposeRepository->create($validatedData);
            }

            DB::commit();
            return $walletTransactionPurpose;
        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }

    }

    public function updateWalletTransactionPurpose($walletTransactionPurposeCode,$validatedData){

        try{
            DB::beginTransaction();

            $walletTransactionPurpose = $this->walletTransactionPurposeRepository->findorFailByTransactionPurposeCode($walletTransactionPurposeCode);

            if($walletTransactionPurpose->is_active){
                throw new Exception('Cannot update because wallet transaction purpose is active');
            }

            if($walletTransactionPurpose->close_for_modification){
                throw new Exception('Cannot update wallet transaction purpose. It is Closed For Modification');
            }

            $checkWalletTransactionPurposeUses = $this->walletTransactionRepository->checkUsesOfWalletTransactionPurposeInTransactions(
                $walletTransactionPurpose->wallet_transaction_purpose_code
            );

            if($checkWalletTransactionPurposeUses){
                throw new Exception('Cannot update wallet transaction purpose . It is used in Wallet Transactions');
            }

            $validatedData['slug'] = make_slug($validatedData['purpose']);

            if(WalletTransactionPurposeHelper::checkIfAlreadyExistsTransactionPurpose(
                $validatedData['user_type_code'],
                $validatedData['slug'],
                $walletTransactionPurpose->wallet_transaction_purpose_code
            )
            ){
                throw new Exception('Wallet Transaction Purpose Already Exists for this
                    Purpose: '.$validatedData["purpose"].' and User Type Code: '.$validatedData['user_type_code']);
            }

            $validatedData['updated_by'] = getAuthUserCode();

            $walletTransactionPurpose = $this->walletTransactionPurposeRepository->update($walletTransactionPurpose,$validatedData);
            DB::commit();
            return $walletTransactionPurpose;
        }catch (Exception $exception){
            DB::rollBack();
            throw  $exception;
        }
    }


    public function deleteWalletTransactionPurpose($walletTransactionPurposeCode){

        try{

           $walletTransactionPurpose = $this->walletTransactionPurposeRepository->findorFailByTransactionPurposeCode($walletTransactionPurposeCode);

            if($walletTransactionPurpose->is_active){
                throw new Exception('Cannot Delete Because Wallet Transaction Purpose it is Active');
            }

            if($walletTransactionPurpose->close_for_modification){
                throw new Exception('Cannot Delete Wallet Transaction Purpose. It is Closed For Modification');
            }

            $checkWalletTransactionPurposeUses = $this->walletTransactionRepository->checkUsesOfWalletTransactionPurposeInTransactions(
                $walletTransactionPurpose->wallet_transaction_purpose_code
            );

            if($checkWalletTransactionPurposeUses){
                throw new Exception('Cannot Delete Wallet  Transaction Purpose . It is used in Transactions');
            }


            DB::beginTransaction();
            $walletTransactionPurpose = $this->walletTransactionPurposeRepository->delete($walletTransactionPurpose);
            DB::commit();

            return  $walletTransactionPurpose;

        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

    public function toggleStatus($walletTransactionPurposeCode){

        try{
            $walletTransactionPurpose = $this->walletTransactionPurposeRepository->findorFailByTransactionPurposeCode($walletTransactionPurposeCode);

            $checkWalletTransactionPurposeUses = $this->walletTransactionRepository->checkUsesOfWalletTransactionPurposeInTransactions(
                $walletTransactionPurpose->wallet_transaction_purpose_code
            );

            if($checkWalletTransactionPurposeUses){
                throw new Exception('Cannot Change Status of Wallet Transaction Purpose. It is used in Wallet Transactions');
            }

            if($walletTransactionPurpose->close_for_modification){
                throw new Exception('Cannot Change Status of Wallet Transaction Purpose. It is Closed For Modification');
            }


            DB::beginTransaction();
            $walletTransactionPurpose = $this->walletTransactionPurposeRepository->toggleStatus($walletTransactionPurpose);
            DB::commit();

            return $walletTransactionPurpose;

        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function getAllTransactionPurposesByPurposeAndUserType($purposeType,$userTypeCode){
        try{
            $purposeTypes = WalletTransactionPurpose::PURPOSE_TYPES;
            if(!in_array($purposeType,$purposeTypes)){
                throw new Exception('Invalid Purpose Type');
            }
            return $this->walletTransactionPurposeRepository->getAllActiveControlTransactionPurposesByPurposeAndUserType($purposeType,$userTypeCode);
        }catch (Exception $exception){
            throw  $exception;
        }

    }





}
