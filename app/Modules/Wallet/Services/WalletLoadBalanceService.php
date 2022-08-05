<?php

namespace App\Modules\Wallet\Services;

use App\Exceptions\Custom\ConnectIpsPaymentException;
use App\Modules\OfflinePayment\Repositories\OfflinePaymentRepository;
use App\Modules\OfflinePayment\Services\OfflinePaymentService;
use App\Modules\PaymentGateway\Repositories\OnlinePaymentMasterRepository;
use App\Modules\PaymentProcessor\Classes\ConnectIPS;
use App\Modules\Store\Classes\StoreOfflineLoadBalance;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\Wallet\Factories\OfflineLoadBalanceFactory;
use App\Modules\Wallet\Factories\OnlineLoadBalanceFactory;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class WalletLoadBalanceService
{
    private $offlinePaymentRepository;
    private $onlinePaymentMasterRepository;
    private $storeRepository;
    private $userRepository;
    private $storeOfflineLoadBalance;
    private $offlinePaymentService;
    public function __construct(
        OfflinePaymentRepository $offlinePaymentRepository,
        StoreRepository $storeRepository,
        OnlinePaymentMasterRepository $onlinePaymentMasterRepository,
        UserRepository $userRepository,
        StoreOfflineLoadBalance $storeOfflineLoadBalance,
        OfflinePaymentService $offlinePaymentService
    ){
        $this->offlinePaymentRepository = $offlinePaymentRepository;
        $this->storeRepository = $storeRepository;
        $this->onlinePaymentMasterRepository = $onlinePaymentMasterRepository;
        $this->userRepository = $userRepository;
        $this->storeOfflineLoadBalance = $storeOfflineLoadBalance;
        $this->offlinePaymentService = $offlinePaymentService;
    }

    public function saveOfflineLoadBalance($validatedData){
        try{
            $user = $this->userRepository->findUserByCode(getAuthUserCode());
            if(!$user){
                throw new Exception('User detail not found !');
            }
            $validatedData['offline_payment_holder_namespace'] = $user->userType->namespace;
            $validatedData['payment_holder_type'] = strtolower(substr($validatedData['offline_payment_holder_namespace'], (strrpos( $validatedData['offline_payment_holder_namespace'],'\\') + 1)));
            $validatedData['offline_payment_holder_code'] = getHolderId($validatedData['offline_payment_holder_namespace']);
             DB::beginTransaction();
            switch ($validatedData['payment_holder_type']){
                case 'store':
                    $offlinePayment = $this->offlinePaymentService->saveOfflinePayment($validatedData);
                    break;
                default:
                    throw new Exception(' This user type is not ready to make load balance :(');
            }

            DB::commit();
            return $offlinePayment;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

    public function respondToOfflinePaymentByAdmin($validatedData,$offlinePaymentCode){
        try{
            $offlinePayment = $this->offlinePaymentRepository
                                  ->findOrFailByPaymentType($offlinePaymentCode,'load_balance');

            if (!$offlinePayment->isPending()){
                throw new Exception(
                    'This '.convertToWords($offlinePayment->payment_for,'_').' payment status : (Code : '.$offlinePayment->offline_payment_code.') cannot be changed at the moment !
                    Since , this payment has been already '.$offlinePayment->verification_status.'
            ',403);
            }

            DB::beginTransaction();
            $validatedData['remarks']= isset($validatedData['remarks']) ? $validatedData['remarks']:null;
            $offlinePaymentData = $this->offlinePaymentRepository->updateVerificationStatus($offlinePayment,$validatedData);

            if ($offlinePaymentData->isVerified()){
                $paymentHolderEntity = $offlinePayment->payment_holder_type;
                $factory = new OfflineLoadBalanceFactory();
                $service = $factory->make($paymentHolderEntity);
                $service->loadBalance($offlinePaymentData, $validatedData);
            }
            DB::commit();
            return $offlinePayment;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function respondToOnlinePaymentByAdmin($onlinePaymentCode){
        try{
            //dd(11);
            $onlinePaymentMaster = $this->onlinePaymentMasterRepository->findOrFailByOnlinePaymentCode($onlinePaymentCode);
           // dd($onlinePaymentMaster);
            if ($onlinePaymentMaster->status == 'verified'){
                throw new ConnectIpsPaymentException('Payment already verified.',['amount'=>convertPaisaToRs($onlinePaymentMaster->amount)]);
            }
            if ($onlinePaymentMaster->status == 'rejected'){
                throw new ConnectIpsPaymentException('Payment was rejected.',['amount'=>convertPaisaToRs($onlinePaymentMaster->amount)]);
            }
            if($onlinePaymentMaster->transaction_type != 'load_balance'){
                throw new Exception('Only Online Load Balance is Handled from here :(');
            }
            $connectIps = new ConnectIPS($onlinePaymentMaster->transaction_id,$onlinePaymentMaster->amount);
            $response = $connectIps->validatePayment();
            $response= json_decode($response);
            $currentDateTime = Carbon::now();
            $validatedData['response'] = json_encode($response);
            $validatedData['response_at'] = $currentDateTime;

            DB::beginTransaction();
            if ($response->status == 'SUCCESS'){
                $validatedData['status'] = 'verified';
            }else{
                $validatedData['status'] = 'rejected';
            }
            $onlinePaymentMaster=$this->onlinePaymentMasterRepository->updateOnlinePayment($onlinePaymentMaster,$validatedData);
            $paymentHolderEntity = strtolower(substr($onlinePaymentMaster->payment_initiator,(strrpos( $onlinePaymentMaster->payment_initiator,'\\') + 1)));

            $factory = new OnlineLoadBalanceFactory();
            $service = $factory->make($paymentHolderEntity);
            $service->loadBalance($onlinePaymentMaster);
            DB::commit();
            return $onlinePaymentMaster;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

}
