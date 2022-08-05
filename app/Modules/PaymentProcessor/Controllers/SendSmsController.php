<?php


namespace App\Modules\SMSProcessor\Controllers;

use App\Modules\SMSProcessor\Jobs\SendSmsJob;
use App\Modules\SMSProcessor\Requests\SmsRequest;
use Illuminate\Http\Request;
use Exception;


class SendSmsController
{
    public function sendSMS(SmsRequest $request)
    {
        try{
            $validatedData = $request->validated();
            $message = $request->message;
            SendSmsJob::dispatch($validatedData['to'],$validatedData['message']);
            return sendSuccessResponse('Message sent');

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }
}
