<?php

namespace App\Modules\PaymentProcessor\Classes;


use App\Modules\PaymentProcessor\Interfaces\PaymentClient;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use File;

class ConnectIPS
{

    private $merchantID;
    private $appID;
    private $appName;
    private $transactionId;
    private $transactionDate;
    private $transactionCurrency;
    private $transactionAmount;
    private $referenceId;
    private $remarks='ips load balance for store';
    private $particulars='ips load balance for store';
//    private $remarks;
//    private $particulars;
    private $token;

    private $gateWayUrl;
    private $paymentValidationUrl;
    private $usernameForValidation;
    private $passwordForValidation;
    private $passwordForCreditorPfx;
    private $creditorPfxPath;


    private $processPaymentString;
    private $validatePaymentString;


    public function __construct($transactionId,$transactionAmount)
    {
       // $this->merchantID = $this->getPaymentClientConfiguration()['merchant_id'];
        $connectIpsConfiguration = $this->getPaymentClientConfiguration();
        $this->merchantID = $connectIpsConfiguration['merchant_id'];
        $this->appID = $connectIpsConfiguration['app_id'];
        $this->appName = $connectIpsConfiguration['app_name'];
        $this->transactionCurrency = $connectIpsConfiguration['transaction_currency'];
        $this->gateWayUrl = $connectIpsConfiguration['gateway_url'];
        $this->paymentValidationUrl = $connectIpsConfiguration['payment_validation_url'];
        $this->usernameForValidation = $connectIpsConfiguration['username_for_validation'];
        $this->passwordForValidation = $connectIpsConfiguration['password_for_validation'];
        $this->passwordForCreditorPfx = $connectIpsConfiguration['password_for_creditor_pfx'];
        $this->creditorPfxPath = $connectIpsConfiguration['creditor_pfx_path'];

        $this->setTransactionId($transactionId);
        $this->setReferenceId($transactionId);
        $this->setTransactionAmount($transactionAmount);
        $this->setTransactionDate(Carbon::now()->toDateString());

    }

    public function getMerchantId(){
        return $this->merchantID;
    }
    public function getAppId(){
        return $this->appID;
    }

    public function getAppName(){
        return $this->appName;
    }

    public function setTransactionId($transactionId){
        $this->transactionId=$transactionId;
    }

    public function getTransactionId(){
        return $this->transactionId;
    }

    public function setTransactionDate($date){
        return $this->transactionDate=$date;
    }

    public function getTransactionDate(){
        return $this->transactionDate;
    }

    public function getTransactionCurrency(){
        return $this->transactionCurrency;
    }

    private function setTransactionAmount($amount){
        if ($amount <= 0){
            throw new Exception('Transaction amount must be greater than 0');
        }
        $this->transactionAmount=$amount;
    }

    public function getTransactionAmount(){
        return $this->transactionAmount;
    }

    public function setReferenceId($referenceId){
        $this->referenceId=$referenceId;
    }

    public function getReferenceId(){
        return $this->referenceId;
    }

    public function setRemarks($remarks){
        $this->remarks = $remarks;
    }

    public function setParticulars($particulars){
        $this->particulars = $particulars;
    }
    public function getRemarks(){
       return $this->remarks;
    }

    public function getParticulars(){
        return $this->particulars;
    }

    public function getGatewayUrl(){
        return $this->gateWayUrl;
    }

    public function getPaymentValidationUrl(){
        return $this->paymentValidationUrl;
    }

    public function getProcessPaymentString(){
        //return '3=428,APPID=MER-428-APP-1,APPNAME=AllPasal,TXNID=' . $validatedData['transaction_id'] . ',TXNDATE=' . $validatedData['transaction_date'] . ',TXNCRNCY=NPR,TXNAMT=' . $validatedData['amount'] . ',REFERENCEID=' . $validatedData['transaction_id'] . ',REMARKS=' . $validatedData['remarks'] . ',PARTICULARS=' . $validatedData['particulars'] . ',TOKEN=TOKEN';
        return 'MERCHANTID='.$this->getMerchantId().',APPID='.$this->getAppId().',APPNAME='.$this->getAppName().',TXNID=' . $this->getTransactionId() . ',TXNDATE=' . $this->getTransactionDate() . ',TXNCRNCY='.$this->getTransactionCurrency().',TXNAMT=' . $this->getTransactionAmount() . ',REFERENCEID=' . $this->getReferenceId() . ',REMARKS=' . $this->getRemarks() . ',PARTICULARS=' . $this->getParticulars() . ',TOKEN=TOKEN';

    }

    public function getValidationString(){
        //format reference
        // "MERCHANTID=428,APPID=MER-428-APP-1,REFERENCEID=".$transactionId.",TXNAMT=".$onlinePaymentMaster->amount."";
        return "MERCHANTID=".$this->getMerchantId().",APPID=".$this->getAppId().",REFERENCEID=".$this->getReferenceId().",TXNAMT=".$this->getTransactionAmount()."";
    }

    public function getPaymentClientConfiguration(): array
    {
        $api = config('payment_gateway.clients.connect_ips.api');
        return config('payment_gateway.clients.connect_ips.'.$api.'');
    }

    public function getIpsRequestData() : array
    {
        $token = $this->generateHash($this->getProcessPaymentString());

        return [
            'MERCHANTID' => $this->getMerchantId(),
            'APPID' =>$this->getAppId(),
            'APPNAME' => $this->getAppName(),
            'TXNID' => $this->getTransactionId(),
            'TXNDATE' =>  $this->getTransactionDate(),
            'TXNCRNCY' => $this->getTransactionCurrency(),
            'TXNAMT' => $this->getTransactionAmount(),
            'REFERENCEID' => $this->getReferenceId(),
            'REMARKS' => $this->getRemarks(),
            'PARTICULARS' => $this->getParticulars(),
            'TOKEN' => $token,
            'ACTIONURL' => $this->getGatewayUrl()
        ];


        /*$message =
                'MERCHANTID=428,APPID=MER-428-APP-1,APPNAME=AllPasal,TXNID=' . $validatedData['transaction_id'] . ',TXNDATE=' . $validatedData['transaction_date'] . ',TXNCRNCY=NPR,TXNAMT=' . $validatedData['amount'] . ',REFERENCEID=' . $validatedData['transaction_id'] . ',REMARKS=' . $validatedData['remarks'] . ',PARTICULARS=' . $validatedData['particulars'] . ',TOKEN=TOKEN';
            $token = $this->generateHash($message);
            $ipsApiRequestData = [
                'MERCHANTID' => 428,
                'APPID' => 'MER-428-APP-1',
                'APPNAME' => 'AllPasal',
                'TXNID' => $validatedData['transaction_id'],
                'TXNDATE' =>  $validatedData['transaction_date'],
                'TXNCRNCY' => 'NPR',
                'TXNAMT' => $validatedData['amount'],
                'REFERENCEID' => $validatedData['transaction_id'],
                'REMARKS' => $validatedData['remarks'],
                'PARTICULARS' => $validatedData['particulars'],
                'TOKEN' => $token,
                'ACTIONURL' => 'https://uat.connectips.com/connectipswebgw/loginpage'
            ];*/
    }

    public function validatePayment(){
        $token  = $this->generateHash($this->getValidationString());
        $data = [
            "merchantId"=>$this->getMerchantId(),
            "appId"=> $this->getAppId(),
            "referenceId" => $this->getReferenceId(),
            "txnAmt" => $this->getTransactionAmount(),
            "token" => $token
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->getPaymentValidationUrl(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic '. base64_encode(''. $this->usernameForValidation.':'.$this->passwordForValidation.''),
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;


        /*$string = "MERCHANTID=428,APPID=MER-428-APP-1,REFERENCEID=".$transactionId.",TXNAMT=".$onlinePaymentMaster->amount."";
        $token  = $this->generateHash($string);
        $data = [
            "merchantId"=>428,
            "appId"=> "MER-428-APP-1",
            "referenceId" => $transactionId,
            "txnAmt" => $onlinePaymentMaster->amount,
            "token" => $token
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://uat.connectips.com/connectipswebws/api/creditor/validatetxn',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic TUVSLTQyOC1BUFAtMTpBYmNkQDEyMw==',
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);*/
    }


    private function generateHash($string)
    {
        // Try to locate certificate file
       /* if (!$cert_store = file_get_contents(public_path('connectips/CREDITOR.pfx'))) {
            echo "Error: Unable to read the cert file\n";
            exit;
        }*/

        if (!$cert_store = file_get_contents($this->creditorPfxPath)) {
            echo "Error: Unable to read the cert file\n";
            exit;
        }
        // Try to read certificate file
        if (openssl_pkcs12_read($cert_store, $cert_info, $this->passwordForCreditorPfx)) {
            if ($private_key = openssl_pkey_get_private($cert_info['pkey'])) {
                $array = openssl_pkey_get_details($private_key);
                // print_r($array);
            }
        } else {
            echo "Error: Unable to read the cert store.\n";
            exit;
        }
        $hash = "";
        if (openssl_sign($string, $signature, $private_key, "sha256WithRSAEncryption")) {
            $hash = base64_encode($signature);
            openssl_free_key($private_key);
        } else {
            echo "Error: Unable openssl_sign";
            exit;
        }
        return $hash;
    }


}

