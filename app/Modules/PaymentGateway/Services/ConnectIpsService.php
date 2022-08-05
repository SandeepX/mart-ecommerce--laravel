<?php


namespace App\Modules\PaymentGateway\Services;


use App\Exceptions\Custom\ConnectIpsPaymentException;
use App\Modules\PaymentGateway\core\PaymentGatewayResponse;
use App\Modules\PaymentGateway\Models\OnlinePaymentMaster;
use App\Modules\PaymentGateway\Repositories\OnlinePaymentMasterRepository;
use App\Modules\PaymentMedium\Models\DigitalWallet;
use App\Modules\PaymentProcessor\Classes\ConnectIPS;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\Wallet\Classes\TransactionNotificationConfiguration;
use App\Modules\Wallet\Interfaces\TransactionConfigurationInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class ConnectIpsService implements TransactionConfigurationInterface
{
    private $onlinePaymentMasterRepository;
    private $transactionNotificationConfiguration;
    private $userRepository;
    private $onlinePaymentService;

    public function __construct(
        OnlinePaymentMasterRepository $onlinePaymentMasterRepository,
        TransactionNotificationConfiguration $transactionNotificationConfiguration,
        UserRepository $userRepository,
        OnlinePaymentService $onlinePaymentService
    ){
        $this->onlinePaymentMasterRepository = $onlinePaymentMasterRepository;
        $this->transactionNotificationConfiguration = $transactionNotificationConfiguration;
        $this->userRepository = $userRepository;
        $this->onlinePaymentService = $onlinePaymentService;
    }

    public function setSMSSendStatus($status)
    {
        $this->transactionNotificationConfiguration->setSMSSendStatus($status);
    }

    public function setMailSendStatus($status)
    {
        // TODO: Implement setMailSendStatus() method.
    }

    public function setWEBNotificationSendStatus($status)
    {
        // TODO: Implement setWEBNotificationSendStatus() method.
    }

    public function processIpsPayment($validatedData)
    {
        try {
            $userDetail=$this->userRepository->findUserByCode(getAuthUserCode());
            if(!$userDetail){
                throw new Exception('User detail not found !');
            }
            $validatedData['initiator_code'] =$userDetail->userType->namespace::where('user_code',getAuthUserCode())->first()->getKey();
            $validatedData['payment_initiator'] = $userDetail->userType->namespace;
            $validatedData['wallet_code'] = (new DigitalWallet())->getConnectIpsCode();
            $validatedData['transaction_type'] = 'load_balance';
            $validatedData['transaction_date'] = Carbon::now()->toDateString();
            $validatedData['amount'] = convertRsToPaisa($validatedData['amount']);

            $isNewTransactionId = true;
            $onlinePaymentMaster = new OnlinePaymentMaster();
            while ($isNewTransactionId) {
                $transactionId = $onlinePaymentMaster->generateTransactionId();
                $validatedData['transaction_id'] = $transactionId;
                $existingTransaction = $this->onlinePaymentMasterRepository->findByTransactionId($transactionId);
                if (!$existingTransaction) {
                    $isNewTransactionId = false;
                }
            }
            $connectIps = new ConnectIPS($validatedData['transaction_id'],$validatedData['amount']);
            $validatedData['remarks']='ips load balance for '.$userDetail->userType->user_type_name.' '.$validatedData['initiator_code'];
            $connectIps->setRemarks($validatedData['remarks']);
            $validatedData['particulars']='ips load balance for '.$userDetail->userType->user_type_name.' '.$validatedData['initiator_code'];
            $connectIps->setParticulars($validatedData['particulars']);
            $ipsApiRequestData = $connectIps->getIpsRequestData();

            $validatedData['request'] = json_encode($ipsApiRequestData);
            $validatedData['request_at'] = Carbon::now();
            $validatedData['status'] ='pending';

            DB::beginTransaction();
            $onlinePaymentMaster=$this->onlinePaymentMasterRepository->saveOnlinePayment($validatedData);

            $data = [
                'payment_partner' => 'Connect Ips',
                'wallet_code' => (new DigitalWallet())->getConnectIpsCode()
            ];
            $this->onlinePaymentService->saveOnlinePaymentWalletPaymentMetaDetails(
                $onlinePaymentMaster,
                $data
            );

            DB::commit();
            return $onlinePaymentMaster;

        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function pay($validatedData)
    {
        try {
            $validatedData['wallet_code'] = (new DigitalWallet())->getConnectIpsCode();
            $validatedData['transaction_date'] = Carbon::now()->toDateString();
            $isNewTransactionId = true;
            $onlinePaymentMaster = new OnlinePaymentMaster();
            while ($isNewTransactionId) {
                $transactionId = $onlinePaymentMaster->generateTransactionId();
                $validatedData['transaction_id'] = $transactionId;
                $existingTransaction = $this->onlinePaymentMasterRepository->findByTransactionId($transactionId);
                if (!$existingTransaction) {
                    $isNewTransactionId = false;
                }
            }
            $validatedData['amount'] = convertRsToPaisa($validatedData['amount']);
            $connectIps = new ConnectIPS($validatedData['transaction_id'],$validatedData['amount']);
            $validatedData['remarks']='investment -'.$validatedData['initiator_code'];
            //$validatedData['remarks']='ips investment';
            $connectIps->setRemarks($validatedData['remarks']);
            //$validatedData['particulars']='ips investment for';
            $validatedData['particulars']='investment -'.$validatedData['initiator_code'];
            $connectIps->setParticulars($validatedData['particulars']);
            $ipsApiRequestData = $connectIps->getIpsRequestData();
            $validatedData['request'] = json_encode($ipsApiRequestData);
            $validatedData['request_at'] = Carbon::now();
            $validatedData['status'] ='pending';

            DB::beginTransaction();
            $onlinePaymentMaster=$this->onlinePaymentMasterRepository->saveOnlinePayment($validatedData);
            $data = [
                'payment_partner' => 'Connect Ips',
                'wallet_code' => (new DigitalWallet())->getConnectIpsCode()
            ];
            $this->onlinePaymentService->saveOnlinePaymentWalletPaymentMetaDetails(
                $onlinePaymentMaster,
                $data
            );
            DB::commit();
            return $onlinePaymentMaster;

        }catch(Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }


    public function validateIpsPaymentWithNotification($transactionId){
         $this->setSMSSendStatus(true);
         return  $this->validateIpsPayment($transactionId);
    }

    public function validateIpsPayment($transactionId)
    {
        try{
            $onlinePaymentMaster = $this->onlinePaymentMasterRepository->findByTransactionId($transactionId);

            if (!$onlinePaymentMaster){
                throw new Exception('Payment not found for the transaction.');
            }
            if ($onlinePaymentMaster->status == 'verified'){
                throw new ConnectIpsPaymentException('Payment already verified.',['amount'=>convertPaisaToRs($onlinePaymentMaster->amount)]);
            }
            if ($onlinePaymentMaster->status == 'rejected'){
                throw new ConnectIpsPaymentException('Payment was rejected.',['amount'=>convertPaisaToRs($onlinePaymentMaster->amount)]);
            }

            $connectIps = new ConnectIPS($transactionId,$onlinePaymentMaster->amount);
            $response = $connectIps->validatePayment();
            $response= json_decode($response);
            $requestData= json_decode($onlinePaymentMaster->request);
            $currentDateTime = Carbon::now();
            //dd($response->status);
            $validatedData['response'] = json_encode($response);
            $validatedData['response_at'] = $currentDateTime;


            DB::beginTransaction();
            if ($response->status == 'SUCCESS'){
                $validatedData['status'] = 'verified';
                $validatedData['admin_status'] = 'accepted';
            }
            else{
                $validatedData['status'] = 'rejected';
                $validatedData['admin_status'] = 'rejected';
            }

            $onlinePaymentMaster=$this->onlinePaymentMasterRepository->updateOnlinePayment($onlinePaymentMaster,$validatedData);

            $paymentGatewayResponse = new PaymentGatewayResponse($onlinePaymentMaster,$validatedData);
            $paymentGatewayResponse->throwParentImplementation();

            DB::commit();
            return $onlinePaymentMaster;

        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }



}
