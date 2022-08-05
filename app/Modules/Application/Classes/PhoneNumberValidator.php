<?php
namespace App\Modules\Application\Classes;
use Illuminate\Support\Facades\Http;

class PhoneNumberValidator{
    public static function validatePhoneNumber($phoneNumber){

        try {

            $validatorsUrl = env('PHONE_NUMBER_VALIDATORS_URL');
            $validatorsKey = env('X_RAPID_API_KEY');
            $validatorCountry = env('PHONE_NUMBER_VALIDATORS_DEFAULT_COUNTRY');
            $validatorHost = preg_replace("(^https?://)", "", $validatorsUrl);

            $response = Http::withHeaders([
                'X-RapidAPI-Host' => $validatorHost,
                'X-RapidAPI-Key' => $validatorsKey
            ])->get($validatorsUrl, [
                'number' => $phoneNumber,
                'country' => $validatorCountry,
            ]);

            if($response['isValidNumber'] == false){
                throw new \Exception("Please Enter Valid Phone Number",400);
            }
            return $response;
        }
        catch(\Exception $exception){
            throw $exception;
        }
    }
}
