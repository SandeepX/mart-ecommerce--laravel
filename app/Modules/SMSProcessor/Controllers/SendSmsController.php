<?php


namespace App\Modules\SMSProcessor\Controllers;

use App\Modules\SMSProcessor\Jobs\SendSmsJob;
use App\Modules\SMSProcessor\Requests\SmsRequest;
use App\Modules\SMSProcessor\Services\SMSService;
use Illuminate\Http\Request;
use Exception;


class SendSmsController
{
    public function sendSMS(SmsRequest $request,SMSService $SMSService)
    {
        try{
            $validatedData = $request->validated();

            $data = [
                'purpose' => $validatedData['purpose'],
                'purpose_code' => $validatedData['purpose_code']
            ];

            $smsResponse = $SMSService->sendSMS(
                convertToArray($validatedData['to']),
                $validatedData['message'],
                $data
            );
            $smsResponseCode = $smsResponse->response_code;
            if ($smsResponseCode != 200) {
                throw new Exception('Message Could not be delivered : '.$smsResponse->response.'');
            }
            return sendSuccessResponse('Message Delivered Successfully');

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }
}
