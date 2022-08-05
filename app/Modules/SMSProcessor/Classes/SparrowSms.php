<?php

namespace App\Modules\SMSProcessor\Classes;


use App\Modules\SMSProcessor\Interfaces\SmsClient;
use Exception;
use Illuminate\Support\Facades\Http;

class SparrowSms implements  SmsClient {

    private $token;
    private $sendSmsUrl;
    private $smsSenderName;

    public function __construct(){
        $this->token = $this->getSparrowSmsConfiguration()['token'];
        $this->sendSmsUrl = $this->getSparrowSmsConfiguration()['sms_send_url'];
        $this->smsSenderName = $this->getSparrowSmsConfiguration()['sender_name'];
    }

    public function getSparrowSmsConfiguration() : array {
       return config('sms.sms_providers.sparrow_sms');
    }


//    public function sendSMS(array $to,string $message){
//        $to = implode(',',$to);
//        try{
//            $args = http_build_query(
//                array(
//                    'token' => $this->token,
//                    'from'  => $this->smsSenderName,
//                    'to'    =>$to,
//                    'text'  => $message)
//            );
//            // $url = "http://api.sparrowsms.com/v2/sms/";
//            # Make the call using API.
//
//
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, $this->sendSmsUrl);
//            curl_setopt($ch, CURLOPT_POST, 1);
//            curl_setopt($ch, CURLOPT_POSTFIELDS,$args);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            // Response
//            $response = curl_exec($ch);
//            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//            curl_close($ch);
//
//            if($status_code == 200){
//               $response = json_decode($response);
//               return $response;
//
//            }
//            if($status_code == 403){
//                $response = json_decode($response);
//                throw new \Exception($response->response);
//            }
//        }catch (\Exception $exception){
//            throw $exception;
//        }
//    }

    public function sendSMS(array $to, string $message)
    {
        try{
            $to = implode(',',$to);
            $response = Http::post($this->sendSmsUrl, [
                'token' => $this->token,
                'from' => $this->smsSenderName,
                'to' => $to,
                'text' => $message,
            ]);

            return json_decode($response);

        }catch(Exception $exception){
            throw $exception;
        }

    }


}

