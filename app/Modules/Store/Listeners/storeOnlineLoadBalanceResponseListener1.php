<?php


namespace App\Modules\Store\Listeners;

use App\Modules\PaymentMedium\Models\DigitalWallet;
use App\Modules\Store\Event\storeOnlineLoadBalanceResponseEvent;
use App\Modules\Store\Helpers\StoreTransactionHelper;
use App\Modules\Store\Repositories\Payment\StoreMiscellaneousPaymentRepository;
use App\Modules\User\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\DB;


class storeOnlineLoadBalanceResponseListener
{
    public $userRepo;
    Public $storeMiscellaneousPaymentRepository;

    public function __construct(UserRepository $userRepo,
                                StoreMiscellaneousPaymentRepository $storeMiscellaneousPaymentRepository
    )
    {
        $this->userRepo = $userRepo;
        $this->storeMiscellaneousPaymentRepository = $storeMiscellaneousPaymentRepository;
    }

    public function handle(storeOnlineLoadBalanceResponseEvent $event)
    {
        DB::beginTransaction();
        try{
            $onlinePaymentData = $event->onlinePaymentData;
            $getUserDetail = $this->userRepo->findUserByCode(getAuthUserCode());
            if(!$getUserDetail){
                throw new Exception('User detail not found !');
            }
            if ($onlinePaymentData['status'] == 'verified'){
                $paymentStatus = true;
            }
            else{
               $paymentStatus = false;
            }

            $requestData= json_decode($onlinePaymentData->request);
            $validatedData['user_code'] = getAuthUserCode();
            $validatedData['store_code'] = $getUserDetail->userType->namespace::where('user_code',getAuthUserCode())->first()->getKey();
            $validatedData['payment_for'] ='load_balance';
            $validatedData['payment_type'] ='wallet';
            $validatedData['online_payment_master_code'] =$onlinePaymentData->online_payment_master_code;
            $validatedData['deposited_by'] = $getUserDetail['name'];
            $validatedData['purpose'] ='load balance';
            $validatedData['transaction_date'] =$onlinePaymentData->request_at;
            $validatedData['contact_phone_no'] = $getUserDetail['login_phone'];
            $validatedData['amount'] =convertPaisaToRs($onlinePaymentData->amount);
            $validatedData['voucher_number'] = $onlinePaymentData->transaction_id;
            $validatedData['verification_status'] = $onlinePaymentData['status'];
            $validatedData['remarks'] =$requestData->REMARKS;
            $storeMiscellaneousPayment = $this->storeMiscellaneousPaymentRepository->save($validatedData);

            $data=[
                [
                    'key' => 'payment_partner',
                    'value' => 'Connect Ips'
                ],[
                    'key' => 'wallet_code',
                    'value' => (new DigitalWallet())->getConnectIpsCode()
                ],
            ];

            $this->storeMiscellaneousPaymentRepository->savePaymentMetaDetail($storeMiscellaneousPayment,$data);
            if ($paymentStatus){
                //$validatedData['transaction_amount'] =convertPaisaToRs($onlinePaymentMaster->amount);
                $convertedAmountInRs =convertPaisaToRs($onlinePaymentData->amount);
                $validatedData['transaction_type'] ='load_balance';
                $currentBalance = StoreTransactionHelper::getLatestStoreCumulativeBalance($validatedData['store_code']);
                $validatedData['transaction_amount'] = $convertedAmountInRs;
                $validatedData['current_balance'] = $currentBalance + $convertedAmountInRs;
                $validatedData['created_by'] = getAuthUserCode();
                $storeBalanceMaster = $this->storeMiscellaneousPaymentRepository->saveTransaction($validatedData);
                $validatedData['store_balance_master_code'] = $storeBalanceMaster->store_balance_master_code;
                $validatedData['store_misc_payment_code'] = $storeMiscellaneousPayment->store_misc_payment_code;
                $this->storeMiscellaneousPaymentRepository->saveLoadBalanceDetail($validatedData);
            }
            DB::commit();
        }catch(Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }
}

