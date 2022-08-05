<?php


namespace App\Modules\SMSProcessor\Services;


use App\Modules\SMSProcessor\Adapters\NepalTelecomAdapter;
use App\Modules\SMSProcessor\Adapters\SparrowSmsAdapter;
use App\Modules\SMSProcessor\Repositories\SmsRepository;

use Exception;

class SMSService
{

    private $smsProvider ;
    private $sparrowSmsAdapter;
    private $nepalTelecomAdapter;
    private $smsRepo;

    public function __construct(
        SparrowSmsAdapter $sparrowSmsAdapter,
        NepalTelecomAdapter $nepalTelecomAdapter,
        SmsRepository $smsRepo
    ){
        $this->smsProvider = config('sms.default');
        $this->sparrowSmsAdapter = $sparrowSmsAdapter;
        $this->nepalTelecomAdapter = $nepalTelecomAdapter;
        $this->smsRepo =  $smsRepo;
    }

    public function setDefaultSmsProvider($smsProvider){
        if(!in_array($smsProvider,[
            'sparrow_sms',
            'nt'
        ])){
            throw new \Exception('No Such Sms Provider in our list');
        }
        $this->smsProvider = $smsProvider;
    }

    public function sendSMS(array $to,string $message,$data=null)
    {
        try {
            $result = $this->processSms($to,$message);

            $smsData['to'] = $to;
            $smsData['message'] = $message;
            $smsData['purpose'] = $data['purpose'];
            $smsData['purpose_code'] = $data['purpose_code'];

            $validatedData['request_body'] = json_encode($smsData);;
            $validatedData['response_body'] = json_encode($result);
            $validatedData['purpose'] = $data['purpose'];
            $validatedData['purpose_code'] = $data['purpose_code'];
            $this->smsRepo->store($validatedData);

            return $result;

        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function processSms(array $to,string $message)
   {
        try{

            $defaultSmsProvider = $this->smsProvider;

            if (!$defaultSmsProvider){
                throw new \Exception('No Sms Provider Found !');
            }


            switch ($defaultSmsProvider){
                case 'sparrow_sms':
                    return $this->sparrowSmsAdapter->sendSMS($to,$message);
                case 'nt':
                    return $this->nepalTelecomAdapter->sendSMS($to,$message);

            }
        }catch(Exception  $e){
            throw $e;
        }
   }


}
