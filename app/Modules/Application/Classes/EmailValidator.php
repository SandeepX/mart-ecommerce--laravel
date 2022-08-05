<?php
namespace App\Modules\Application\Classes;
use App\Exceptions\Custom\EmailRequestLimitException;
use Illuminate\Support\Facades\Http;


class EmailValidator{

    public static function validateEmail($email){
        try{

            $validatorURL = env('EMAIL_VALIDATORS_URL');
            $validatorKey = env('X_RAPID_API_KEY');
            $validatorHost = preg_replace("(^https?://)", "", $validatorURL);

            $response = Http::withHeaders([
                'X-RapidAPI-Host'=>$validatorHost,
                'X-RapidAPI-Key'=>$validatorKey,
            ])->get($validatorURL,[
                'domain'=>$email
            ]);

           if($response->status() == 429){
                throw new EmailRequestLimitException(
                    'Email Verified API request exceeded:
                    You have exceeded the MONTHLY quota for requests on your current plan',
                    429
                );
            }

            if($response['block'] == true){
                throw new \Exception(
                   'Please Enter Valid Email Address',
                    400
                );
            }

           return $response['block'];

        }catch(\Exception $exception){
            throw $exception;
        }
    }
}
